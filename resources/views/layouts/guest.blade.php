<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HotelHQ') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Custom CSS for Animated Background and Glass -->
        <style>
            body {
                background-color: #050505;
                margin: 0;
                padding: 0;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                overflow: hidden;
                position: relative;
                font-family: 'Figtree', sans-serif;
                color: #fff;
            }

            /* Animated Background Orbs */
            .orb-container {
                position: absolute;
                top: 0; left: 0; width: 100%; height: 100%;
                overflow: hidden;
                z-index: -1;
            }
            .orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(120px);
                opacity: 0.5;
                mix-blend-mode: screen;
            }
            .orb-indigo {
                background-color: #4f46e5;
                width: 500px; height: 500px;
                top: -100px; left: -100px;
                animation: float 12s infinite alternate ease-in-out;
            }
            .orb-purple {
                background-color: #9333ea;
                width: 400px; height: 400px;
                bottom: -50px; right: -50px;
                animation: float 10s infinite alternate-reverse ease-in-out;
            }
            .orb-blue {
                background-color: #2563eb;
                width: 300px; height: 300px;
                top: 40%; left: 30%;
                animation: float 15s infinite alternate ease-in-out;
            }

            @keyframes float {
                0% { transform: translate(0, 0) scale(1); }
                50% { transform: translate(100px, 50px) scale(1.1); }
                100% { transform: translate(-50px, 100px) scale(0.9); }
            }

            /* Glass Card */
            .glass-card {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                border-radius: 24px;
                width: 100%;
                max-width: 420px;
                padding: 40px;
                position: relative;
                z-index: 10;
                transition: all 0.3s ease;
            }
            .glass-card:hover {
                background: rgba(255, 255, 255, 0.05);
                border-color: rgba(255, 255, 255, 0.2);
                box-shadow: 0 0 40px rgba(99, 102, 241, 0.2);
            }

            /* Top glowing line on card */
            .glass-card::before {
                content: '';
                position: absolute;
                top: 0; left: 0; width: 100%; height: 1px;
                background: linear-gradient(90deg, transparent, rgba(129, 140, 248, 0.8), transparent);
                opacity: 0.5;
                transition: opacity 0.3s ease;
            }
            .glass-card:hover::before {
                opacity: 1;
            }

            /* Inputs */
            .glass-input {
                width: 100%;
                background: rgba(0, 0, 0, 0.4);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 12px;
                padding: 12px 16px 12px 40px;
                color: #fff !important;
                font-size: 14px;
                transition: all 0.3s ease;
                outline: none;
                -webkit-text-fill-color: #ffffff !important;
            }
            .glass-input:focus {
                border-color: #818cf8;
                background: rgba(0, 0, 0, 0.6);
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
            }
            .glass-input::placeholder {
                color: #6b7280;
            }

            /* Button */
            .glass-btn {
                width: 100%;
                background: rgba(79, 70, 229, 0.8);
                border: 1px solid rgba(129, 140, 248, 0.5);
                border-radius: 12px;
                padding: 14px;
                color: white;
                font-weight: bold;
                font-size: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                cursor: pointer;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            .glass-btn:hover {
                background: rgba(99, 102, 241, 1);
                box-shadow: 0 0 20px rgba(99, 102, 241, 0.4);
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>
        
        <!-- Animated Background -->
        <div class="orb-container">
            <div class="orb orb-indigo"></div>
            <div class="orb orb-purple"></div>
            <div class="orb orb-blue"></div>
        </div>

        <!-- Logo -->
        <div style="margin-bottom: 30px; z-index: 10; display: flex; align-items: center; gap: 12px;">
            <div style="height: 56px; width: 56px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 30px rgba(99,102,241,0.3);">
                <svg style="width: 32px; height: 32px; color: #818cf8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <span style="font-weight: 900; font-size: 36px; color: white; letter-spacing: -1px;">Hotel<span style="color: #818cf8;">HQ</span></span>
        </div>

        <!-- Glass Card -->
        <div class="glass-card">
            {{ $slot }}
        </div>
        
        <div style="margin-top: 40px; font-size: 12px; color: #6b7280; z-index: 10;">
            &copy; {{ date('Y') }} HotelHQ Management System. All rights reserved.
        </div>
    </body>
</html>