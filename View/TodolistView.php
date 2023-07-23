<?php

namespace View;

use Repository\TodolistRepository;
use Service\TodolistService;
use function Function\input;

class TodolistView
{
    private TodolistService $todolistService;
    public function __construct(array $todoList)
    {
        $this->todolistService = new TodolistService(new TodolistRepository($todoList));
    }

    public function runApp():void
    {
        echo "-------------Aplikasi Todolist--------------\n";
        while (true){
            $text = <<<TEXT
                Perhatikan Instruksi dibawah ini!!
                1. Show Todolist
                2. Add Todolist
                3. Delete Todolist
                4. Update Todolist
                x. Selesai
             TEXT;
            echo $text.PHP_EOL;
            $choice = input("Masukan pilihan anda");
            switch ($choice){
                case "1":
                    $this->show();
                    break;
                case "2":
                    $this->add();
                    break;
                case "3":
                    $this->delete();
                    break;
                case "4":
                    $this->update();
                    break;
                case "x" or "X":
                    goto end;
                default:
                    echo "Pilihan anda salah\n";
                    break;
            }
            $check = input("Lanjut?? (Y\N)");
            if(strtolower($check) === "n") break;
        }
        end:
        echo "----------Selesai-----------\n";
    }

    public function show():void
    {
        echo "---Show Todolist---\n";
        foreach ($this->todolistService->findAll() as $no=>$value)
        {
            echo ($no+1)." $value\n";
        }
    }

    public function add():void
    {
        echo "---Add Todolist---\n";
        $todo = input("Masukan todo baru (x batal)");
        if($todo==='x'){
            echo "Tambah todo dibatalkan\n";
            return;
        }
        try {
            $this->todolistService->add($todo);
            echo "Berhasil ditambahkan\n";
        }catch (\Exception $exception)
        {
            echo $exception->getMessage().PHP_EOL;
        }
    }

    public function delete():void
    {
        echo "---Delete Todolist---\n";
        $this->show();
        $no = input("Masukan no todo yang ingin dihapus (x batal)");
        if($no === 'x'){
            echo "Delete todo dibatalkan\n";
            return;
        }
        try {
            $this->todolistService->delete($no-1);
            echo "Berhasil dihapus\n";
        }catch (\Exception $exception)
        {
            echo $exception->getMessage().PHP_EOL;
        }
    }

    public function update():void
    {
        echo "---Update Todolist---\n";
        $this->show();
        $no = input("Masukan no todo yang ingin diupdate (x batal)");
        if($no === 'x'){
            echo "Update todo dibatalkan\n";
            return;
        }
        $todo = input("Masukan todo");
        try {
            $this->todolistService->update($no-1,$todo);
            echo "Berhasil diperbarui\n";
        }catch (\Exception $exception)
        {
            echo $exception->getMessage().PHP_EOL;
        }
    }
}