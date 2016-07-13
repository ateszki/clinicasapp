<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class generarTurnos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generarTurnos {fecha}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'genera turnos para agendas de la fecha indicada';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        	$this->info('Generar Turnos');
		$this->info('Iniciado: '.date('Y-m-d H:i:s'));
		$c = new \CentroOdontologoEspecialidadController;
		$resultado = $c->generarTurnos(NULL,$this->argument('fecha'));
		$this->info($resultado);
		$this->info('Terminado: '.date('Y-m-d H:i:s'));
    }
}
