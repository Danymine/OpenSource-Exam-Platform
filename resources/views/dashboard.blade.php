<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (Auth::User()->roles == 0)

                        <h2>Benvenut Studente {{ Auth::User()->name }}</h2>
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Labore ullam, vero, voluptatem nam perferendis animi nesciunt quam ad maiores eligendi expedita maxime, officiis minima delectus necessitatibus. Quas ab ex laboriosam.</p>
                       

                        <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Nome
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Data
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Codice
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            Programmazione 1
                                        </th>
                                        <td class="px-6 py-4">
                                            27/12/2023
                                        </td>
                                        <td class="px-6 py-4">
                                            5Cbm34
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                           Architettura degli Elaboratori
                                        </th>
                                        <td class="px-6 py-4">
                                            05/01/2024
                                        </td>
                                        <td class="px-6 py-4">
                                            6pOcs3
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    @else
                    
                        <h2>Benvenut Docente {{ Auth::User()->name }}</h2>
                        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Labore ullam, vero, voluptatem nam perferendis animi nesciunt quam ad maiores eligendi expedita maxime, officiis minima delectus necessitatibus. Quas ab ex laboriosam.</p>
                       

                        <div class="relative overflow-x-auto" style="margin-top: 2em">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Nome
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Data
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Codice
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            Programmazione 1
                                        </th>
                                        <td class="px-6 py-4">
                                            27/12/2023
                                        </td>
                                        <td class="px-6 py-4">
                                            5Cbm34
                                        </td>
                                    </tr>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                           Architettura degli Elaboratori
                                        </th>
                                        <td class="px-6 py-4">
                                            05/01/2024
                                        </td>
                                        <td class="px-6 py-4">
                                            6pOcs3
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
