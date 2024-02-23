<x-app-layout>
  
    <x-slot name="header">
        <h4>
            {{ __('Crea Esame') }}
        </h4>
        <hr stile="border-top: 1px solid #000000; width: 90%;" />
    </x-slot>

    <div class="container">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

        <form method="POST" action="{{ route('create_exame_step1') }}">
            @csrf
            <div class="row mt-4">
            <!-- Input Titolo -->
            <div class="col-md-6">
                <div class="form-group">
                    <label for="titolo">{{ __('Titolo') }}</label>
                    <input type="text" class="form-control" id="title" name="title" required value="{{ session()->has('exame_step1') ? session('exame_step1')['title'] : '' }}">
                </div>
                </div>

                <!-- Input Materia -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="materia">{{ __('Materia') }}</label>
                        <input type="text" class="form-control" id="materia" name="subject" required value="{{ session()->has('exame_step1') ? session('exame_step1')['subject'] : '' }}">
                    </div>
                </div>
            </div>

            <!-- Textarea Descrizione -->
            <div class="form-group">
                <label for="descrizione">{{ __('Descrizione') }}</label>
                <textarea class="form-control" id="descrizione" name="description" rows="3" required> {{ session()->has('exame_step1') ? session('exame_step1')['description'] : '' }} </textarea>
            </div>


            <!-- Bottoni -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <a class="btn btn-danger text-white" id="annulla" href="{{ route('exit_exame_process') }}">{{ __('Annulla') }}</a>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" class="btn btn-info">{{ __('Indietro') }}</button>
                    <button type="submit" class="btn btn-primary ml-2" id="avanti">{{ __('Avanti') }}</button>
                </div>
            </div>

        </form>
    </div>

</x-app-layout>

