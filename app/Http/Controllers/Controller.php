<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function __construct()
    {
        if (method_exists($this, 'middleware')) {
            $this->middleware(function ($request, $next) {
                if (auth()->check() && auth()->user()->role !== 'admin') {
                    abort(403, 'Akses hanya untuk admin.');
                }
                return $next($request);
            })->only([
                'index', 'create', 'store', 'show', 'edit', 'update', 'destroy'
            ]);
        }
    }

    public function middlewareGuru()
    {
        return function ($request, $next) {
            if (auth()->check() && auth()->user()->role !== 'guru') {
                abort(403, 'Akses hanya untuk guru.');
            }
            return $next($request);
        };
    }
    public function middlewareSiswa()
    {
        return function ($request, $next) {
            if (auth()->check() && auth()->user()->role !== 'siswa') {
                abort(403, 'Akses hanya untuk siswa.');
            }
            return $next($request);
        };
    }
}
