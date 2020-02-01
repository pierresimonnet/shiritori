<?php

namespace App\Controller;

use App\Entity\Shiritori;
use App\Entity\Word;
use App\Form\WordType;
use App\JishoApi\JishoApi;
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
     * @Route("/shiritori/{id}", name="shiritori")
     * @param Shiritori $shiritori
     * @param Request $request
     * @return Response
     */
    public function index(Shiritori $shiritori, Request $request)
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

            if($request->isXmlHttpRequest()){
                $data = [
                    'type' => 'success',
                    'title' => 'valid entry',
                    'success' => $newWord->getWord() . " a bien été ajouté",
                    'word' => $newWord->getWord(),
                    'count' => $shiritori->getWords()->count(),
                ];
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
     * @Route("/delete/{id}", name="delete_shiritori", methods={"DELETE"})
     * @param Shiritori $shiritori
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Shiritori $shiritori, Request $request){
        if($this->isCsrfTokenValid('delete', $request->get('_token'))){
            $em = $this->getDoctrine()->getManager();
            $em->remove($shiritori);
            $em->flush();
            $this->addFlash('success', "Le shiritori a bien été supprimé");
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
}
