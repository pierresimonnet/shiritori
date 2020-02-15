<?php

namespace App\Controller;

use App\Entity\Shiritori;
use App\Entity\Word;
use App\Form\WordType;
use App\JishoApi\JishoApi;
use App\Repository\WordRepository;
use App\Utils\WordSplit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShiritoriController extends AbstractController
{
    /**
     * @Route("/shiritori/{id<\d+>}", name="shiritori")
     * @param Shiritori $shiritori
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function index(Shiritori $shiritori, Request $request, WordRepository $wordRepository)
    {
        $newWord = new Word();
        $newWord->setShiritori($shiritori);

        $form = $this->createForm(WordType::class, $newWord);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest() && $form->isSubmitted() && !$form->isValid()){
            $errors = $this->getErrorsFromForm($form);
            $data = [
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $errors
            ];

            return new JsonResponse($data, 422);
        }

        if($form->isSubmitted() && $form->isValid()){
            if(null !== $newWord->getWord()){
                $jisho = new JishoApi($newWord->getWord());
                $jisho->getJishoExist();
                $newWord->setReading($jisho->getReading())
                        ->setSenses($jisho->getSenses());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($newWord);
            $em->flush();

            if($shiritori->getType() === "compete"){
                $last = WordSplit::split($newWord->getWord())['last'];
                $reponse = new JishoApi($last);
                $reponse->getJishoExist();
                $next = [];
                foreach ($reponse->getAllData() as $words){
                    if(count(WordSplit::split($words['japanese'][0]['word'])) === 2){
                        $first = WordSplit::split($words['japanese'][0]['word'])['first'];
                        if($first === $last && mb_strlen($words['japanese'][0]['word']) === 2 && !$wordRepository->findOneByWordAndShiritori($words['japanese'][0]['word'], $shiritori)) $next[] = $words;
                    }
                }
                $nextWord = $next[0];
                if($nextWord) {
                    $appWord = new Word();
                    $appWord->setWord($nextWord['japanese'][0]['word']);
                    $appWord->setReading($nextWord['japanese'][0]['reading']);
                    $appWord->setSenses($nextWord['senses'][0]['english_definitions']);
                    $appWord->setShiritori($shiritori);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($appWord);
                    $em->flush();
                }
            }

            if($request->isXmlHttpRequest()){
                $data = [
                    'type' => 'success',
                    'title' => 'valid entry',
                    'success' => $newWord->getWord() . " a bien été ajouté.",
                    'word' => $newWord->getWord(),
                    'id' => $newWord->getId(),
                    'count' => $shiritori->getWords()->count(),
                ];

                if($shiritori->getType() === "compete"){
                    $data['next'] = $appWord->getWord();
                    $data['nextId'] = $appWord->getId();
                }

                return new JsonResponse($data, 200);
            }

            return $this->redirectToRoute('shiritori', ['id' => $shiritori->getId()]);
        }

        return $this->render('shiritori/play.html.twig', [
            'controller_name' => 'ShiritoriController',
            'shiritori' => $shiritori,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id<\d+>}", name="delete_shiritori", methods="POST")
     * @param Shiritori $shiritori
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function delete(Shiritori $shiritori, Request $request){
        if($this->isCsrfTokenValid('delete', $request->get('_token'))){
            $em = $this->getDoctrine()->getManager();
            $em->remove($shiritori);
            $em->flush();

            if($request->isXmlHttpRequest()){
                return new Response(null, 204);
            }else{
                $this->addFlash('success', "Le shiritori a bien été supprimé");
            }
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();

        foreach ($form->getErrors() as $error) {
            if($error instanceof FormError){
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    /**
     * @Route("/shiritori/word/{id<\d+>}", name="word-info", methods="GET")
     * @param Word $word
     * @return JsonResponse
     */
    public function getWordInfo(Word $word)
    {
        $data = [
            'reading' => $word->getReading(),
            'sense' => $word->getSenses()
        ];

        return new JsonResponse($data, 200);
    }
}
