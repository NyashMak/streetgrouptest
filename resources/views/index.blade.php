@extends('layout.app')
@section('body')
<div class="mt-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
            <div class="">
                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Upload CSV File</h2>
                <hr>
                <div>
                    <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <input class="form-control" type="file" name="file">
                            @error('file')
                                <p class="text-danger small w-100">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-10">
                            <button class="btn btn-primary" type="submit" name="submit">Upload File</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
            <div>
                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Home Owners List</h2>

                <div class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    <table class="table table-primary">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Initial</th>
                                <th scope="col">Last Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($people))
                                @foreach ($people as $person)
                                    <tr>
                                        <td scope="row">{{ $person['title'] }}</td>
                                        <td>{{ $person['first_name'] }}</td>
                                        <td>{{ $person['initial'] }}</td>
                                        <td>{{ $person['last_name'] }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection