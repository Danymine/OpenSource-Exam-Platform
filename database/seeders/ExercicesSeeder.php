<?php

namespace Database\Seeders;

//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExercicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $domande_scolastiche = array(
            // Matematica
            ["Risolvi l'equazione x^2 - 4 = 0.", "Matematica"],
            ["Calcola l'area di un triangolo con base 8 cm e altezza 10 cm.", "Matematica"],
            ["Qual è il risultato di 3/4 + 2/5?", "Matematica"],
        
            // Scienze
            ["Spiega il ciclo dell'acqua.", "Scienza"],
            ["Quali sono i tre stati della materia? Descrivili brevemente.", "Scienza"],
            ["Cosa è la fotosintesi e quale ruolo gioca nella vita delle piante?", "Scienza"],
        
            // Storia
            ["Descrivi gli eventi principali della Rivoluzione Francese.", "Storia"],
            ["Chi erano i personaggi chiave della Guerra Civile Americana?", "Storia"],
            ["Qual è stata l'importanza della scoperta dell'America da parte di Cristoforo Colombo?", "Storia"],
        
            // Lingue
            ["Coniuga il verbo 'amare' al tempo passato remoto.", "Lingue"],
            ["Traduci la frase 'Hello, how are you?' in italiano.", "Lingue"],
            ["Scrivi una breve composizione sul tuo soggetto preferito in lingua straniera.", "Lingue"],
        
            // Informatica
            ["Cosa è un algoritmo e come si differenzia da un programma?", "Informatica"],
            ["Descrivi l'importanza della sicurezza informatica.", "Informatica"],
            ["Qual è la differenza tra software libero e software proprietario?", "Informatica"],
            ["Descrivi il concetto di programmazione orientata agli oggetti.", "Informatica"],
            ["Cosa significa il termine 'algoritmo' e fornisci un esempio pratico.","Informatica"],
            ["Spiega la differenza tra linguaggi di programmazione interpretati e compilati.","Informatica"],
            ["Qual è il ruolo di una variabile in programmazione?","Informatica"],
            ["Cosa sono le strutture dati e quali sono alcuni esempi comuni?","Informatica"],
            ["Spiega il concetto di 'scope' in programmazione.","Informatica"],
            ["Descrivi il concetto di ereditarietà in programmazione orientata agli oggetti.","Informatica"],
            ["Cosa sono le eccezioni e come vengono gestite in molti linguaggi di programmazione?","Informatica"],
            ["Spiega la differenza tra una chiave primaria e una chiave esterna in un database.","Informatica"],
            ["Cos'è il versionamento del software e qual è l'importanza di utilizzare un sistema di controllo delle versioni?","Informatica"],
            ["Qual è il ruolo di un indice in un database relazionale?","Informatica"],
            ["Cosa significa il termine 'client-server' in un contesto di rete?","Informatica"],
            ["Descrivi il concetto di programmazione asincrona.","Informatica"],
            ["Cos'è una funzione hash e in che modo può essere utilizzata per garantire l'integrità dei dati?","Informatica"],
            ["Spiega la differenza tra una coda e uno stack nelle strutture dati.","Informatica"],
            ["Qual è la differenza tra HTTP e HTTPS?","Informatica"],
            ["Cosa sono i web service e come vengono utilizzati nella programmazione web?","Informatica"],
            ["Descrivi il concetto di normalizzazione del database.","Informatica"],
            ["Cosa sono i thread e qual è il loro ruolo nella programmazione concorrente?","Informatica"],
            ["Spiega il concetto di virtualizzazione e in che modo viene utilizzato nei data center.","Informatica"],
            ["Qual è la differenza tra un linguaggio di programmazione compilato e uno interpretato?","Informatica"],
            ["Cosa significa il termine 'Big Data' e quali sono le sfide associate alla sua gestione?","Informatica"],
            ["Descrivi il modello di sviluppo Agile e fornisce un esempio di un framework Agile.","Informatica"],
            ["Qual è il ruolo di un firewall nella sicurezza informatica?","Informatica"],
            ["Cosa sono i cookie nei contesti web e come vengono utilizzati per il tracciamento degli utenti?","Informatica"],
            ["Spiega il concetto di machine learning e fornisce un esempio di suo utilizzo.","Informatica"],
            ["Qual è il ruolo di un sistema operativo in un computer?","Informatica"],
            ["Descrivi la differenza tra software open source e software proprietario.","Informatica"],
            ["Cosa sono le reti neurali artificiali e in che modo vengono utilizzate nell'apprendimento automatico?","Informatica"],
            ["Spiega il concetto di cloud computing e fornisce vantaggi e svantaggi associati.", "Informatica"]
        );
        $diff = [
            "Alta",
            "Media",
            "Bassa"
        ];

        for( $i = 0; $i < 44; $i++){

            DB::table('exercises')->insert([
                'user_id' => 1,
                'name' => $domande_scolastiche[$i][0],
                'question' => $domande_scolastiche[$i][0],
                'score' => rand(1,10),
                'difficulty' => $diff[rand(0,2)],
                'subject' => $domande_scolastiche[$i][1],
                'type' => 'Risposta Aperta',
            ]);
        }
    }
}
