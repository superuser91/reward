<?php

namespace Vgplay\Reward\Console;

use Illuminate\Console\Command;
use Vgplay\Reward\Models\Transaction;

class ExportLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:log {game} {type?} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export log user purchased item';

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
     * @return int
     */
    public function handle()
    {
        $transactions = Transaction::with('product.shop')
            ->when($this->argument('game'), function ($q) {
                $q->whereHas('product', function ($product) {
                    $product->whereHas('shop', function ($shop) {
                        $shop->where('game_id', $this->argument('game'));
                    });
                });
            })
            ->when($this->argument('type'), function ($q) {
                $q->whereHas('product', function ($product) {
                    $product->where('type', $this->argument('type'));
                });
            })
            ->orderBy('user_id', 'ASC')
            ->get();

        $bar = $this->output->createProgressBar(count($transactions));

        $fp = fopen(storage_path($filename = 'export_log.csv'), 'w');
        fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, ['Time', 'GAME ID', 'VGP ID', 'Item', 'Server', 'Nhân vật']);

        foreach ($transactions as $transaction) {
            fputcsv($fp, [
                $transaction->created_at,
                $transaction->product->shop->game_id,
                $transaction->vgp_id,
                $transaction->reward->name,
                $transaction->server,
                $transaction->character
            ]);
            $bar->advance();
        }

        fclose($fp);
        $bar->finish();
        $this->newLine(2);
        $this->line('Export successfully. Log is available to download at: ' . $this->downloadLink($filename));

        return 0;
    }

    protected function downloadLink($filename)
    {
        return sprintf(
            "<a href='%s'>%s</a>",
            route('admin.log.download', ['filename' => $filename]),
            route('admin.log.download', ['filename' => $filename])
        );
    }
}
