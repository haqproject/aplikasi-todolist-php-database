<?php

namespace Repository {
    
    use Entity\Todolist;

    interface TodolistRepository 
    {

        function save(Todolist $todolist): void;

        function remove(int $number): bool;

        function findAll(): array;
    }

    class TodolistRepositoryImpl implements TodolistRepository {

        public array $todolist = array();

        private \PDO $connection;

        public function __construct(\PDO $connection)
        {
            $this->connection = $connection;
        }

        function save(Todolist $todolist): void
        {
            // $number = sizeof($this->todolist) + 1; //mengetahui todo list terakhir

            // $this->todolist[$number] = $todolist;    

            $sql = "INSERT INTO todolist(todo) VALUES (?)";
            $statement = $this->connection->prepare($sql);
            $statement->execute([$todolist->getTodo()]);
            
        }

        function remove(int $number): bool
        {
            //  if($number > sizeof($this->todolist)){
            //      return false;
            //  }

            // for($i = $number; $i < sizeof($this->todolist); $i++){
            //     $this->todolist[$number] = $this->todolist[$i + 1];
            // }

            // unset($this->todolist[sizeof($this->todolist)]);
            // return true;

            //periksa dulu apakah id yang akan di delet ada?
            $sql = "SELECT id FROM todolist WHERE id = ?";
            $statement = $this->connection->prepare($sql);
            $statement->execute([$number]);
            
            //jika ada maka lanjukan ke proses delete
            if($statement->fetch()){
                    
                $sql = "DELETE FROM todolist WHERE id = ?";
                $statement = $this->connection->prepare($sql);
                $statement->execute([$number]);

                return true;
            }else{

                return false;
            }
        }

        function findAll(): array
        {
            // return $this->todolist;

            $sql = "SELECT id, todo FROM todolist";
            $statement = $this->connection->prepare($sql);
            $statement->execute();

            $result = [];

            foreach($statement as $row){
                $todolist = new Todolist();
                $todolist->setId($row['id']);
                $todolist->setTodo($row['todo']);
                $result[] = $todolist;
            }

            return $result;
        }
    }
}