<?php

namespace App\Exports;

use App\Link;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class LinksExport implements FromView, WithColumnFormatting
{
	public $links;

	public function __construct($links)
	{
		$this->links = $links;
	}

    public function view(): View
    {
    	return view('links.export', [
    		'links' => $this->links
    	]);
    }

    public function columnFormats(): array
    {
        return [
            'B' => '+###-###-####',
        ];
    }
}
