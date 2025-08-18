<?php 

    // require_once "../classes/QueryBuilder.php";

    class Validate
    {

        private $data = [];
        public $errors_msg = [];
        private $rules_validate = [];

        private function required(string $field, string $input_user)
        {
            if(empty($input_user) || trim( $input_user) === ''){
                $this->errors_msg[$field][] = "Поле пустое";
                return false;
            }
            return true;
        }
        
        public function email(string $field, string $input_user)
        {
            if(!filter_var($input_user, FILTER_VALIDATE_EMAIL)){
                $this->errors_msg[$field][] = "Введенные данные не является почтой";
                return false;
            }
            return true;
        }

        private function same(string $field, string $input_user, string $fieldSame)
        {
            $data_same = $this->getData();

            if($input_user != $data_same[$fieldSame]){
                $this->errors_msg[$field][] = "Данные с полем {$fieldSame} не совпадают";
                return false;
            }

            return true;
        }

        private function exists(string $field, string $input_user, string $table, string $column = null)
        {
            if($column == null){
                $column = $field;
            }

            $exists_select = new QueryBuilder();
            $row = $exists_select->select($table)->where([$column, $input_user])->one();

            if(empty($row)){
                $this->errors_msg[$field][] = "Данной {$column} не существет";
                return false;
            }

            return true;
        }

        private function unique(string $field, string $input_user, string $table, string $column = null)
        {
            if($column == null){
                $column = $field;
            }

            $unique_select = new QueryBuilder();
            $row = $unique_select->select($table)->where([$column, $input_user])->one();

            if(!empty($row)){
                $this->errors_msg[$field][] = "Данная {$column} существует и не может использовать дважды";
                return false;
            }

            return true;
        }

        private function min(string $field, string $input_user, int $minSymbol)
        {
            if(strlen($input_user) < $minSymbol){
                $this->errors_msg[$field][] = "Слишком мало символов, минимум - {$minSymbol}";
                return false;
            }

            return true;
        }

        private function max(string $field, string $input_user, int $maxSymbol)
        {
            if(strlen($input_user) > $maxSymbol){
                $this->errors_msg[$field][] = "Слишком много символов, максимум - {$maxSymbol}";
                return false;
            }

            return true;
        }

        public function rules(array $rules)
        {
            $this->rules_validate = $rules;
        }


        public function validation(array $data, array $rules = null)
        {

            if($rules == null){
                $rules = $this->rules_validate;
            }

            foreach($rules as $field => $rule){

                // $value = htmlspecialchars($data[$field], ENT_QUOTES, "UTF-8") ?? null;

                foreach($rule as $elem){
                    preg_match('/(\w+)(?:\((.*?)\))?/', $elem, $match); 

                    $method = $match[1];
                    $param = isset($match[2]) ? explode(",", $match[2]) : [];

                    array_unshift($param, $field, $data[$field]);
                    // array_unshift($param, $field, $value);

                    call_user_func_array([$this, $method], $param);
                }
                $this->data[$field] = $data[$field];
                // $this->data[$field] = $value;
            }

            return empty($this->errors_msg);
        }

        public function getErrors()
        {
            return $this->errors_msg;
        }

        public function getData()
        {
            return $this->data;
        }


    }

//                     | Часть выражения       | Что делает                                                                                                                                  |
//                     | --------------------- | ------------------------------------------------------------------------------------------------------------------------------------------- |
//                     | `/ ... /`             | Ограничители регулярного выражения                                                                                                          |
//                     | `(\w+)`               | **Первая группа**: одна или более букв, цифр или подчёркиваний (то есть имя метода, например `max`, `min`, `required`)                      |
//                     | `(?: ... )`           | **Негруппирующая скобочная группа** (внутреннее содержимое не сохраняется как отдельный `match`)                                            |
//                     | `\( ... \)`           | Ищет **буквальный символ `(` и `)`**, то есть скобки вокруг аргументов                                                                      |
//                     | `(.*?)`               | **Вторая группа**: захватывает всё внутри скобок, минимально возможное количество символов (это и есть аргументы — например, `255` или `8`) |
//                     | `?` (после `(?:...)`) | Делает всю конструкцию со скобками **необязательной** — правило может быть без аргументов (`required`)                                      |

?>

