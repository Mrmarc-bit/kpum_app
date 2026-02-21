<?php

namespace Tests\Unit\Controllers\Admin;

use Tests\TestCase;
use App\Http\Controllers\Admin\ScannerController;
use App\Services\QrCodeService;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Mockery;

class ScannerControllerTest extends TestCase
{
    protected $qrService;
    protected $controller;


}
