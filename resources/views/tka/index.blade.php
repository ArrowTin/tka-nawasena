@extends('layouts.exam-app')

@section('content')
    <div class="rbt-lesson-content-wrapper">
        <div class="rbt-lesson-rightsidebar overflow-hidden">
            <div class="lesson-top-bar">
                <div class="lesson-top-left">
                    <div class="rbt-lesson-toggle">
                        <button class="lesson-toggle-active btn-round-white-opacity" title="Toggle Sidebar"><i
                                class="feather-arrow-left"></i></button>
                    </div>
                    <h5>Tes Kemampuan Akademik - Matematika</h5>
                </div>
                <div class="lesson-top-right">
                    <div class="rbt-btn-close">
                        <a href="#" title="Go Back to Course" class="rbt-round-btn"><i class="feather-x"></i></a>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="quize-top-meta">
                    <div class="quize-top-left">
                        <span>Soal No: <strong>{{ $currentQuestionNumber }}/{{ $totalQuestions }}</strong></span>
                        <span>Attempts Allowed: <strong>1/{{ $totalAttempts }}</strong></span>
                    </div>
                    <div class="d-flex flex-wrap gap-2 justify-content-center" id="question-nav">
                        @foreach ($questions as $question)
                            <a href="{{ route('tka.index', ['question' => $question['id']]) }}"
                                class="btn btn-sm {{ $question['id'] == $currentQuestionNumber ? 'btn-primary' : 'btn-outline-primary' }}"
                                style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 0;">
                                {{ $question['id'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        @if ($currentQuestion['question_image'])
                            <img src="{{ $currentQuestion['question_image'] }}" alt="Gambar Soal" class="img-fluid">
                        @else
                            <div class="text-muted text-center p-5" style="background-color: #f5f5f5; border-radius: 8px;">
                                <i class="feather-image" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mt-3">Gambar Soal (Jika Ada)</p>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="rbt-dashboard-table table-responsive mobile-table-750 mt--30 overflow-hidden">
                            <div class="mb--80" id="question-{{ $currentQuestion['id'] }}">
                                <div class="rbt-single-quiz">
                                    <h4>{{ $currentQuestionNumber }}. {{ $currentQuestion['question_text'] }}</h4>
                                    <div class="row g-3 mt-3">
                                        @foreach ($currentQuestion['options'] as $option)
                                            <div class="col-lg-6">
                                                <div class="rbt-form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="answer[{{ $currentQuestion['id'] }}]"
                                                        id="option-{{ $currentQuestion['id'] }}-{{ $option['label'] }}"
                                                        value="{{ $option['label'] }}">
                                                    <label class="form-check-label"
                                                        for="option-{{ $currentQuestion['id'] }}-{{ $option['label'] }}">
                                                        <strong>{{ $option['label'] }}.</strong> {{ $option['text'] }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="bg-color-extra2 ptb--15 overflow-hidden">
                <div class="rbt-button-group">
                    @if ($currentQuestionNumber > 1)
                        <a class="rbt-btn icon-hover icon-hover-left btn-md bg-primary-opacity"
                            href="{{ route('tka.index', ['question' => $currentQuestionNumber - 1]) }}">
                            <span class="btn-icon"><i class="feather-arrow-left"></i></span>
                            <span class="btn-text">Previous</span>
                        </a>
                    @else
                        <a class="rbt-btn icon-hover icon-hover-left btn-md bg-primary-opacity disabled" href="#"
                            style="opacity: 0.5; pointer-events: none;">
                            <span class="btn-icon"><i class="feather-arrow-left"></i></span>
                            <span class="btn-text">Previous</span>
                        </a>
                    @endif

                    @if ($currentQuestionNumber < $totalQuestions)
                        <a class="rbt-btn icon-hover btn-md"
                            href="{{ route('tka.index', ['question' => $currentQuestionNumber + 1]) }}">
                            <span class="btn-text">Next</span>
                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                        </a>
                    @else
                        <a class="rbt-btn icon-hover btn-md disabled" href="#"
                            style="opacity: 0.5; pointer-events: none;">
                            <span class="btn-text">Next</span>
                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                        </a>
                    @endif

                    @if ($currentQuestionNumber == $totalQuestions)
                        <div class="submit-btn">
                            <a class="rbt-btn icon-hover btn-md btn-gradient" href="#">
                                <span class="btn-text">Submit Jawaban</span>
                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
