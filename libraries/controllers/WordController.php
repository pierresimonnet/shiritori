<?php

namespace Controllers;

use JishoAPI;
use Models\Word;

require_once dirname(__DIR__).DIRECTORY_SEPARATOR."/utils.php";

class WordController extends Controller
{
    protected $modelName = Word::class;

    public function index(){
        $data = $this->model->findAll();
        if ($this->isAjax()){
            echo json_encode($data);
            die();
        }
        $pageTitle = "Kanji Shiritori";
        render("index", compact("pageTitle", "data"));
    }

    public function insert(){
        $data = $this->model->findAll();
        $lastEntry = $this->model->findLast();
        $lastEntrySplit = preg_split("//u", $lastEntry->word, -1, PREG_SPLIT_NO_EMPTY);
        $lastEntryLastChar = end($lastEntrySplit);
        $pattern = "/[a-zA-Z0-9０-９あ-んア-ンー。、？！＜＞： 「」（）｛｝≪≫〈〉《》【】『』〔〕［］・\n\r\t\s\(\)　]/u";
        $error = "";

        if(isset($_POST['input'])){
            if(!empty($_POST['input'])){
                $input = trim(htmlentities($_POST['input']));
                $inputSplit = preg_split("//u", $input, -1, PREG_SPLIT_NO_EMPTY);
                $inputFirstChar = reset($inputSplit);

                if(preg_match($pattern, $input))
                {
                    $error = "ブー！Le mot doit être écrit en kanji.";
                }
                elseif(mb_strlen($input) < 2 || mb_strlen($input) > 2)
                {
                    $error = "ブー！$input comprend ". mb_strlen($input). " kanjis. Le mot doit faire 2 kanjis.";
                }elseif(!empty($data) && $input === $lastEntry->word)
                {
                    $error = "ブー！Le mot $input est identique au mot précédemment saisi ($lastEntry->word).";
                }
                elseif(!empty($data) && $lastEntryLastChar !== $inputFirstChar)
                {
                    $error = "ブー！Le premier kanji de $input ($inputFirstChar) ne correspond pas au dernier kanji de $lastEntry->word ($lastEntryLastChar).";
                }else{
                    // Recherche du mot dans le dictionnaire
                    $jishoApi = new JishoAPI();
                    $result = $jishoApi->getJisho($input);

                    if ($result === true){
                        $this->model->insert($input);
                        $success = "$input a bien été ajouté.";
                        if($this->isAjax()){
                            echo json_encode(['status' => "success", 'message' => $success]);
                            die();
                        }
                        $_SESSION['success'] = $success;
                    }else{
                        $error = "ブー！Le mot $input n'existe pas.";
                    }

                }
            }else{
                $error = "Le champ est vide.";
            }
        }

        if($error){
            if($this->isAjax()){
                http_response_code(400);
                echo json_encode(['status' => "error", 'message' => $error]);
                die();
            }
            $_SESSION['error'] = $error;
        }

        redirect("index.php");
    }

    public function reset(){
        if(!isset($_POST['hiddenreset'])){
            die("Une erreur est survenue");
        }
        $this->model->reset();
        $success = "Le shiritori a bien été supprimé.";
        if($this->isAjax()){
            echo json_encode(['status' => "info", 'message' => $success]);
            die();
        }
        $_SESSION['success'] = $success;

        redirect("index.php");
    }
}
