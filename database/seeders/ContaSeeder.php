<?php

namespace Database\Seeders;

use App\Models\Conta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        if(!Conta::where('nome','Energia')->first()) {
            Conta::create([
                'nome'=>'Energia',
                'valor'=>'147.45',
                'vencimento'=>'2024-12-23',
            ]);
        }

        if(!Conta::where('nome','Internet')->first()) {
            Conta::create([
                'nome'=>'Internet',
                'valor'=>'89.99',
                'vencimento'=>'2024-10-05',
            ]);
        }
        
        if(!Conta::where('nome','Telefone')->first()) {
            Conta::create([
                'nome'=>'Telefone',
                'valor'=>'55.60',
                'vencimento'=>'2024-09-12',
            ]);
        }
        
        if(!Conta::where('nome','Condomínio')->first()) {
            Conta::create([
                'nome'=>'Condomínio',
                'valor'=>'450.00',
                'vencimento'=>'2024-11-25',
            ]);
        }
        
        if(!Conta::where('nome','Aluguel')->first()) {
            Conta::create([
                'nome'=>'Aluguel',
                'valor'=>'1200.00',
                'vencimento'=>'2024-12-01',
            ]);
        }
        
        if(!Conta::where('nome','Seguro')->first()) {
            Conta::create([
                'nome'=>'Seguro',
                'valor'=>'300.00',
                'vencimento'=>'2024-12-15',
            ]);
        }
        
        if(!Conta::where('nome','TV a cabo')->first()) {
            Conta::create([
                'nome'=>'TV a cabo',
                'valor'=>'150.00',
                'vencimento'=>'2024-09-25',
            ]);
        }
        
        if(!Conta::where('nome','Gasolina')->first()) {
            Conta::create([
                'nome'=>'Gasolina',
                'valor'=>'200.00',
                'vencimento'=>'2024-10-20',
            ]);
        }
        
        if(!Conta::where('nome','Academia')->first()) {
            Conta::create([
                'nome'=>'Academia',
                'valor'=>'75.00',
                'vencimento'=>'2024-09-30',
            ]);
        }
        
        if(!Conta::where('nome','Cartão de crédito')->first()) {
            Conta::create([
                'nome'=>'Cartão de crédito',
                'valor'=>'500.00',
                'vencimento'=>'2024-11-10',
            ]);
        }
    }
}
