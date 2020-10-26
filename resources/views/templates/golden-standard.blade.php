<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="{{ asset('assets/css/resumes/gold-standard.css') }}" />
    </head>
    <body class="resume-pdf resume-gold-standard">
        <div class="print-paper">
            <span class="resume-title">{{ $resume->user->full_name }}</span>
            <span class="contact-info">
                {{ $resume->first_address->city }}, {{ $resume->first_address->province }}
                <br/>
                @if($resume->user->user_phones->first())
                    {{ $resume->user->user_phones->first()->phone_number }}
                @endif
                <br/>
                {{ $resume->user->email }}
                <br/>
                LinkedIn URL (todo)
            </span>
            <br/>

            <div>
                <span class="heading">Summary</span>
                @if(isset($resume->resumeSummaries))
                    <ul>
                        @foreach ($resume->resumeSummaries as $summary)
                            <li>{{ $summary->name }}</li>
                        @endforeach
                    </ul>
                @endif
                <br/>
            </div>


            <span class="heading">Work Experience</span>
            @if(count($resume->resumeWorkExperiences) > 0)
                @foreach( $resume->resumeWorkExperiences as $work )
                    <div class="section">
                        <span class="work-experience-title float-left">{{ $work->position_title }}</span>
                        <span class="work-experience-dates float-right">{{ $work->position_start_date}} to {{ $work->position_end_date ? $work->position_end_date : 'present' }}</span>
                        <br/>
                        <span class="work-experience-company">{{ $work->position_company }}</span>
                        <ul class="work-experience-description-list">
                            @foreach( $work->resumeDescriptions as $description )
                                <li class="work-experience-description">
                                    {{ $description->description }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @endif

            @if(count($resume->resumeEducations))
                <span class="heading">Education</span>
                @foreach( $resume->resumeEducations as $education )
                    <div>
                        <span class="education-experience-title float-left">{{ $education->school_name }}</span>
                        <span class="education-experience-dates float-right">{{ $education->start_date}} to {{ $education->end_date ? $education->end_date : 'present' }}</span>
                        <br/>
                    </div>
                @endforeach
            @endif

            <span class="heading">Affiliations &amp; Hobbies</span>
            <br/>
        </div>
    </body>
</html>
