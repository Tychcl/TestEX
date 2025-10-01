<?php
namespace Html\event\info;

use Models\EventlevelQuery;

class Info{

    private function renderTemplate($template, $data = []) {
        extract($data);
        ob_start();
        include $template;
        return ob_get_clean();
    }

    public function add(){
        $q = EventlevelQuery::create()->find()->toArray();
        $options = '';
        foreach($q as $e){
            $id = $e['Id'];
            $name = $e['Name'];
            $options = $options.'<option value="'.$id.'">'.$name.'</option>';
        }
        //echo json_encode($options);
        //<option value="">-- Выберите категорию --</option>
        $html = $this->renderTemplate('add/template.php', [
            'options' => $options
        ]);
        return $html;
    }

}
?>