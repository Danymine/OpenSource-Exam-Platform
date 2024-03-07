<x-app-layout>
  
    <x-slot name="header">
        <h4>
            {{ __('Crea Esercitazione') }}
        </h4>
        <hr style="border-top: 1px solid #0000004a width: 90%;" />
    </x-slot>

    <div class="container">
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="container p-4 rounded" style="background-color: #fff; box-shadow: 0.15rem 0.25rem 0 rgb(33 40 50 / 15%); border: 1px solid rgba(0,0,0,.125);">

        <div class="small-container">
            <div class="circle-container">
                <div class="circle active-circle">1</div>
                <div class="circle">2</div>
                <div class="circle">3</div>
                <div class="connector-line"></div>
                <div class="connector-line"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('create_practice_step1') }}">
            @csrf
            <div class="row mt-4">
            <!-- Input Titolo -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="titolo">{{ __('Titolo') }}</label>
                    <input type="text" class="form-control" id="title" name="title" required value="{{ session()->has('exame_step1') ? session('exame_step1')['title'] : '' }}" placeholder="{{ __('Inserisci il Titolo') }}">
                </div>
                </div>

                <!-- Input Materia -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="materia">{{ __('Materia') }}</label>
                        <input type="text" class="form-control" id="materia" name="subject" required value="{{ session()->has('exame_step1') ? session('exame_step1')['subject'] : '' }}" placeholder="{{ __('Inserisci la Materia') }}">
                    </div>
                </div>
            </div>

            <!-- Textarea Descrizione -->
            <div class="form-group">
                <label for="descrizione">{{ __('Descrizione') }}</label>
                <textarea class="form-control" id="descrizione" name="description" rows="3" placeholder="{{ __('Inserisci una Descrizione') }}" required> {{ session()->has('exame_step1') ? session('exame_step1')['description'] : '' }} </textarea>
            </div>


            <!-- Bottoni -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a class="btn btn-danger text-white" id="annulla" href="{{ route('exit_practice_process') }}">{{ __('Annulla') }}</a>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-info">{{ __('Indietro') }}</button>
                    <button type="submit" class="btn btn-primary ml-2" id="avanti">{{ __('Avanti') }}</button>
                </div>
            </div>

        </form>
    </div>

</x-app-layout>

