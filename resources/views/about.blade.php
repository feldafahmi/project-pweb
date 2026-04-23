@extends('layouts.app')

@push('styles')
    @vite(['resources/css/about.css'])
@endpush

@section('content')
    <div class="breadcrumb-container">
        <div class="mu-container">
            <a href="{{ route('about') }}"><i class="fas fa-home"></i></a>
            <span class="mx-2">></span>
            <span class="current-page">Profil Perusahaan</span>
        </div>
    </div>

    <section class="about-hero">
        <div class="mu-container">
            <h1>Mencetak Juara Indonesia</h1>
            <p>Berdiri sejak 2025, Mark-Up berkomitmen mencetak juara-juara lomba
                <br>bergengsi secara inklusif untuk seluruh mahasiswa Indonesia
            </p>
        </div>
    </section>

    <section class="founder-section mu-container">
        <div class="section-header">
            <h3 class="font-italic">Founder & Co-Founder</h3>
            <h2>Mark-Up Indonesia</h2>
        </div>

        <div class="founder-grid">
            <div class="founder-card">
                <div class="profile-img-wrapper">
                    <img src="{{ asset('img/4624.jpg') }}" alt="Foto Founder 1">
                </div>
                <div class="founder-info">
                    <h4>Gibran Rakabuming</h4>
                    <p class="role">Wakil Presiden Wakanda</p>
                    <div class="quote">
                        <i class="fas fa-quote-left text-primary"></i>
                        <p>"Akademi Competition memberikan akses dan kesempatan yang sama bagi semua mahasiswa. Bersama
                            kami, siapapun bisa jadi juara!"</p>
                    </div>
                </div>
            </div>

            <div class="founder-card">
                <div class="profile-img-wrapper">
                    <img src="{{ asset('img/4624.jpg') }}" alt="Foto Founder 2">
                </div>
                <div class="founder-info">
                    <h4>Gibran Rakabuming</h4>
                    <p class="role">Wakil Presiden Wakanda</p>
                    <div class="quote">
                        <i class="fas fa-quote-left text-primary"></i>
                        <p>"Akademi Competition memberikan akses dan kesempatan yang sama bagi semua mahasiswa. Bersama
                            kami, siapapun bisa jadi juara!"</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section mu-container">
        <div class="section-header">
            <h3 class="font-italic" style="margin-bottom: 40px;">Our Executive Team</h3>
        </div>

        <div class="team-grid">
            <div class="team-card">
                <div class="profile-img-wrapper">
                    <img src="{{ asset('img/4624.jpg') }}" alt="Foto CEO">
                    <span class="badge-role">CEO</span>
                </div>
                <h4>Gibran Rakabuming</h4>
                <p class="role">Wakil Presiden Wakanda</p>
                <div class="social-links">
                    <a href="#" class="social-icon email"><i class="fas fa-envelope"></i></a>
                    <a href="#" class="social-icon linkedin"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="team-card">
                <div class="profile-img-wrapper">
                    <img src="{{ asset('img/4624.jpg') }}" alt="Foto CEO">
                    <span class="badge-role">CEO</span>
                </div>
                <h4>Gibran Rakabuming</h4>
                <p class="role">Wakil Presiden Wakanda</p>
                <div class="social-links">
                    <a href="#" class="social-icon email"><i class="fas fa-envelope"></i></a>
                    <a href="#" class="social-icon linkedin"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="team-card">
                <div class="profile-img-wrapper">
                    <img src="{{ asset('img/4624.jpg') }}" alt="Foto CEO">
                    <span class="badge-role">CEO</span>
                </div>
                <h4>Gibran Rakabuming</h4>
                <p class="role">Wakil Presiden Wakanda</p>
                <div class="social-links">
                    <a href="#" class="social-icon email"><i class="fas fa-envelope"></i></a>
                    <a href="#" class="social-icon linkedin"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

        </div>
    </section>
@endsection
