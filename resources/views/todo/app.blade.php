<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FamiList</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" href="{{ asset('images/favicon(2)-32x32.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #F2EFE7;
            font-family: 'Roboto', sans-serif;
            color: #333;
        }
        .container {
            max-width: 960px;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .card-body {
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
        }
        .form-control, .btn {
            border-radius: 50px;
        }
        .input-group .form-control {
            border: 1px solid #ddd;
        }
        .btn-primary {
            background-color: #1976d2;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1369c0;
        }
        .btn-edit {
            background-color: #1976d2;
            border: none;
            border-radius: 50px !important;
            transition: background-color 0.3s ease;
        }
        .btn-subtask {
            background-color: #1976d2;
            border: none;
            border-radius: 50px !important;
            transition: background-color 0.3s ease;
        }
        .btn-edit:hover {
            background-color: #1369c0;
        }
        .btn-danger {
            background-color: #f44336;
            border: none;
        }
        .btn-danger:hover {
            background-color: #e53935;
        }
        .btn-success {
            background-color: #05a794;
            border: none;
        }
        .btn-success:hover {
            background-color: #028d76;
        }
        .list-group-item {
            cursor: pointer;
            background-color: #fff;
            border: none;
            border-radius: 8px;
            margin-bottom: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .list-group-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .modal-content {
            border-radius: 12px;
            background-color: #f8f9fa;
        }
        .modal-header {
            background-color: #f1f3f5;
            border-bottom: none;
        }
        .modal-title {
            color: #343a40;
            font-weight: bold;
        }
        .modal-body {
            padding: 2rem;
        }
        .form-select {
            border-radius: 50px;
            background-color: #fafafa;
        }
        .alert {
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .btn-group button {
            padding: 6px 12px;
            margin-right: 5px;
            font-size: 14px;
        }
        .pagination {
    display: flex;  /* Pastikan tetap horizontal */
    justify-content: center;
    align-items: center;
    gap: 5px;
}

.pagination li {
    display: inline-flex; 
}

.pagination .page-item.active .page-link {
    background-color: #1976d2; 
    color: white;
    border-color: #1976d2;
}

.pagination .page-item .page-link {
    display: inline-flex; 
    align-items: center;
    justify-content: center;
    min-width: 40px;
    padding: 8px 12px;
    border-radius: 50px;
    text-decoration: none;
    color: #333;
    background-color: #fff;
    border: 1px solid #ddd;
    transition: all 0.2s ease-in-out;
}

.pagination .page-item .page-link:hover {
    background-color: #f1f1f1;
    border-color: #bbb;
}


    .custom-radius input, .custom-radius select, .custom-radius button {
        border-radius: 12px; /* Ganti dengan ukuran radius yang diinginkan */
    }

        
    </style>
</head>
<body>

<div class="container py-5">
    @if (Auth::check())
    <x-navbar>{{ Auth::user()->name }}</x-navbar>
    @endif

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card mb-3">
            <h3 class="text-center py-1" style="font-family: 'Roboto', sans-serif; cursor: pointer;" onclick="munculFormTask()">Tambah tugas</h3>
            @if (session('success'))
            <div class="alert alert-success mx-1" id="success-alert">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger mx-1" id="error-alert">
                {{ session('error') }}
            </div>
            @endif
            @if (session('errorTD'))
            <div class="alert alert-danger mx-1" id="error-alert">
                {{ session('errorTD') }}
            </div>
            @endif
            @if (session('errorD'))
            <div class="alert alert-danger mx-1" id="error-alert">
                {{ session('errorD') }}
            </div>
            @endif
            @if (session('warning'))
            <div class="alert alert-warning mx-1" id="warning-alert">
                {{ session('warning') }}
            </div>
            @endif
            @if ($errors->any())
                <script>
                    sessionStorage.setItem('showForm', 'true');
                </script>
            @endif
            @if (session('error'))
                <script>
                    sessionStorage.setItem('showForm', 'true');
                </script>
            @endif

            <div class="card-body" id="card-body" style="display: none; opacity: 0; transition: opacity 0.3s ease;">
        
                <form action="{{ route('tasks.store') }}" id="tambahTugas" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama tugas" value="{{ old('name') }}" required>
                    </div>
        
                    <div class="mb-3">
                        <input type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi" value="{{ old('deskripsi') }}">
                    </div>
        
                    <div class="mb-3 w-50">
                        <label for="tenggat_waktu" class="form-label">Tenggat waktu</label>
                        <input type="datetime-local" class="form-control" id="tenggat_waktu" name="tenggat_waktu" oninput="this.blur()" required>
                    </div>
                    <div class="mb-3 w-50">
                        <label for="reminder" class="form-label">Reminder</label>
                        <input type="datetime-local" class="form-control" id="reminder" name="reminder" oninput="this.blur()" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="prioritas" class="form-label">Prioritas</label>
                        <select class="form-select" id="prioritas" name="prioritas">
                            <option value="1" {{ old('prioritas') == '1' ? 'selected' : '' }}>Prioritas 1</option>
                            <option value="2" {{ old('prioritas') == '2' ? 'selected' : '' }}>Prioritas 2</option>
                            <option value="3" {{ old('prioritas') == '3' ? 'selected' : '' }}>Prioritas 3</option>
                        </select>
                    </div>
        
                    <div class="text-center">
                        <button type="submit" onclick="return confirm('Yakin data sudah benar?')" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>


        {{-- sort fitur --}}
        <label for="sortOptions">Sort By</label>
        <div class="w-100 d-flex justify-content-between">
            <form method="GET" action="{{ route('tasks.index') }}">
                <div class="form-group my-2">
                   
                    
                    <select class="form-select w-auto" name="sort_option" aria-label="Sort options" onchange="this.form.submit()">
                        <option selected value="default" {{ request('sort_option') == 'default' ? 'selected' : '' }}>Pilih opsi sortir</option>
                        <option value="name_asc" {{ request('sort_option') == 'name_asc' ? 'selected' : '' }}>Nama Tugas (A-Z)</option>
                        <option value="name_desc" {{ request('sort_option') == 'name_desc' ? 'selected' : '' }}>Nama Tugas (Z-A)</option>
                        <option value="tenggat_asc" {{ request('sort_option') == 'tenggat_asc' ? 'selected' : '' }}>Deadline (Terdekat)</option>
                        <option value="tenggat_desc" {{ request('sort_option') == 'tenggat_desc' ? 'selected' : '' }}>Deadline (Terlama)</option>
                        <option value="selesai" {{ request('sort_option') == 'selesai' ? 'selected' : '' }}>Tugas Selesai</option>
                        <option value="belum" {{ request('sort_option') == 'belum' ? 'selected' : '' }}>Tugas Belum Selesai</option>
                        <option value="terlambat" {{ request('sort_option') == 'terlambat' ? 'selected' : '' }}>Tugas Terlambat</option>
                        <option value="prioritas_1" {{ request('sort_option') == 'prioritas_1' ? 'selected' : '' }}>Prioritas 1</option>
                        <option value="prioritas_2" {{ request('sort_option') == 'prioritas_2' ? 'selected' : '' }}>Prioritas 2</option>
                        <option value="prioritas_3" {{ request('sort_option') == 'prioritas_3' ? 'selected' : '' }}>Prioritas 3</option>
                    </select>
                   
                
                    
                </div>
            </form>
            <form action="{{ route('tasks.deleteAll') }}" id="formHapusAll" method="POST">
                @csrf
                @method('delete')
                <button type="button" class="btn btn-danger btn-sm" title="Hapus Semua" onclick="hapusAll()" ><i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
        

            <!-- Task List -->
            <div class="card">
                <div class="card-body cursor-pointer">
                    <ul class="list-group">
                        @if ($tasks->isEmpty())
                        <li class="list-group-item">Tidak ada tugas</li>
                    @else
                        @foreach ($tasks as $task)
                        <li class="list-group-item d-flex align-items-start"  >
                            <div class="d-flex flex-column">
                                <span>
                                    @if($task->status == "selesai")
                                    <del>{{ $task->name }}</del>
                                    @elseif($task->status == 'terlambat')
                                    <del class="text-danger">{{ $task->name }} (Terlambat)</del>
                                    @else
                                    {{ $task->name }}
                                    @endif
                                </span>
                            </div>

                            <div class="btn-group mt-2 ms-auto">
                                <!-- Mark as Done/Undone -->
                                <form action="{{ route('tasks.toggle-done', $task->id) }}" method="POST">
                                    @csrf
                                    @method('put')
                                    @if($task->status == 'belum')
                                    <button class="btn btn-success btn-sm" title="Selesaikan">
                                        ✔
                                    </button>
                                    @endif
                                </form>
                                @if($task->status == 'belum')
                                <button class="btn btn-edit btn-primary btn-sm" title="Edit" data-bs-toggle="modal" data-bs-target="#editTaskModal-{{ $task->id }}">
                                    ✎
                                </button>
                                @endif
                                @if($task->status == 'belum')
                                <button class="btn btn-edit btn-primary btn-sm" title="Tambah Subtugas" data-bs-toggle="modal" data-bs-target="#subtaskModal-{{ $task->id }}">
                                    +
                                </button>
                                @endif
                                
                                <button class="btn btn-edit btn-primary btn-sm" title="Lihat" data-bs-toggle="modal" data-bs-target="#subtaskViewModal-{{ $task->id }}">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </button>
                            

                                <!-- Delete Task -->
                                <form action="{{ route('tasks.delete', $task->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">✕</button>
                                </form>
                            </div>
                        </li>

                        <!-- Edit Task Modal -->
            <div class="modal fade" id="editTaskModal-{{ $task->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTaskModalLabel">Edit Task - {{ $task->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Form Edit Task -->
                            <form action="{{ route('tasks.edit', $task->id) }}" id="formEditTugas{{ $task->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Tugas</label>
                                    <input type="text" class="form-control" id="taskName" name="name" required value="{{ $task->name }}" >
                                </div>

                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="taskDescription" name="deskripsi" rows="3" >{{ $task->deskripsi }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="tenggat_waktu" class="form-label">Tenggat Waktu</label>
                                    <input type="datetime-local" class="form-control" id="taskDeadline" oninput="this.blur()" name="tenggat_waktu" value="{{ \Carbon\Carbon::parse($task->tenggat_waktu)->format('Y-m-d\TH:i') }}" >
                                </div>
                                <div class="mb-3">
                                    <label for="reminder" class="form-label">Reminder</label>
                                    <input type="datetime-local" class="form-control" id="taskReminder" oninput="this.blur()" name="reminder" value="{{ \Carbon\Carbon::parse($task->reminder)->format('Y-m-d\TH:i') }}" >
                                </div>

                                <div class="mb-3">
                                    <label for="taskPriority" class="form-label">Prioritas</label>
                                    <select class="form-select" id="taskPriority" name="prioritas" >
                                        <option value="1" {{ $task->prioritas == 1 ? 'selected' : '' }}>Prioritas 1</option>
                                        <option value="2" {{ $task->prioritas == 2 ? 'selected' : '' }}>Prioritas 2</option>
                                        <option value="3" {{ $task->prioritas == 3 ? 'selected' : '' }}>Prioritas 3</option>
                                    </select>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin mengedit?')">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Subtask Modal -->
            <div class="modal fade" id="subtaskModal-{{ $task->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header d-flex w-100 position-relative">
                            <div class="d-flex flex-column w-100">
                                <h5 class="modal-title mb-2">Subtugas untuk tugas {{ $task->name }}</h5>
                                <p class="modal-title text-muted mb-0">Deskripsi        : {{ $task->deskripsi ?? "Tidak ada deskripsi" }}</p>
                                <p class="modal-title text-muted mb-0">Tenggat waktu    : {{ $task->tenggat_waktu }}</p>
                                <p class="modal-title text-muted mb-0">Prioritas    : {{ $task->prioritas }}</p>
                                <p class="modal-title text-muted mb-0">Reminder    : {{ $task->reminder }}</p>
                            </div>
                            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0" data-bs-dismiss="modal"></button>
                        </div>
                        @if (session()->has('errorES_' . $task->id))
                            <div class="alert alert-danger">
                                {{ session('errorES_' . $task->id) }}
                            </div>
                        @endif
                        @if (session()->has('warningES_' . $task->id))
                            <div class="alert alert-warning">
                                {{ session('warningES_' . $task->id) }}
                            </div>
                        @endif
                        @if (session()->has('successES_' . $task->id))
                            <div class="alert alert-success">
                                {{ session('successES_' . $task->id) }}
                            </div>
                        @endif

                        @foreach ($task->subtasks as $subtask)
                            @if (session()->has('errorES_' . $subtask->id))
                                <div class="alert alert-danger">
                                    {{ session('errorES_' . $subtask->id) }}
                                </div>
                            @endif

                            @if (session()->has('successES_' . $subtask->id))
                                <div class="alert alert-success">
                                    {{ session('successES_' . $subtask->id) }}
                                </div>
                            @endif
                            @if (session()->has('warningES_' . $subtask->id))
                                <div class="alert alert-warning">
                                    {{ session('warningES_' . $subtask->id) }}
                                </div>
                            @endif
                        @endforeach

                    
                        <div class="modal-body">
                            <!-- Form Tambah Subtask -->
                            <form action="{{ route('subtasks.store', $task->id) }}" method="POST">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="name" placeholder="Nama subtugas" required>
                                    <input type="datetime-local" class="form-control" oninput="this.blur()" name="tenggat_waktu" required>
                                    <button class="btn btn-primary" type="submit">Tambah</button>
                                </div>
                            </form>

                            <!-- Daftar Subtask -->
                            <ul class="list-group">
                               
                                @foreach ($task->subtasks->sortByDesc('created_at') as $subtask)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        @if($subtask->status == "selesai")
                                        <del>{{ $subtask->name }}</del>
                                        @elseif($subtask->status == 'terlambat')
                                        <del class="text-danger">{{ $task->name }} (Terlambat)</del>
                                        @else
                                        {{ $subtask->name }}
                                        @endif
                                    </span>
                                    <div class="btn-group">
                                        <form action="{{ route('subtasks.toggle-done', $subtask->id) }}" method="POST">
                                            @csrf
                                            @method('put')
                                            @if($subtask->status == 'belum')
                                            <input type="hidden" name="modal_origin" value="subtaskModal">
                                            <button class="btn btn-success btn-sm" title="Selesai">
                                                ✔
                                            </button>
                                            @endif
                                        </form>
                                        @if($subtask->status == 'belum')
                                        <button class="btn btn-subtask btn-primary btn-sm" title="Edit" data-bs-toggle="modal" data-bs-target="#editSubtaskModal-{{ $subtask->id }}">
                                            ✎
                                        </button>
                                        @endif

                                        <!-- Delete Subtask -->
                                        <form action="{{ route('subtasks.delete', $subtask->id) }}" id="formHapusSub{{ $subtask->id }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="modal_origin" value="subtaskModal">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">✕</button>
                                        </form>
                                    </div>
                                </li>
                                @endforeach
                               
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- subtask view modal --}}
            <div class="modal fade" id="subtaskViewModal-{{ $task->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header d-flex w-100 position-relative">
                            <div class="d-flex flex-column w-100">
                                <h5 class="modal-title mb-2">Subtugas untuk tugas {{ $task->name }}</h5>
                                <p class="modal-title text-muted mb-0">Deskripsi        : {{ $task->deskripsi ?? "Tidak ada deskripsi" }}</p>
                                <p class="modal-title text-muted mb-0">Tenggat waktu    : {{ $task->tenggat_waktu }}</p>
                                <p class="modal-title text-muted mb-0">Prioritas    : {{ $task->prioritas }}</p>
                                <p class="modal-title text-muted mb-0">Reminder    : {{ $task->reminder }}</p>
                            </div>
                            <button type="button" class="btn-close btn-sm position-absolute top-0 end-0" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            
                            <ul class="list-group">
                                @if ($task->subtasks->isEmpty())
                                <li class="list-group-item d-flex justify-content-between align-items-center">Tidak ada subtugas</li>
                                @else
                                @foreach ($task->subtasks->sortByDesc('created_at') as $subtask)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        @if($subtask->status == "selesai")
                                        <del>{{ $subtask->name }}</del>
                                        @elseif($subtask->status == 'terlambat')
                                        <del class="text-danger">{{ $task->name }} (Terlambat)</del>
                                        @else
                                        {{ $subtask->name }}
                                        @endif
                                    </span>
                                    <div class="btn-group">
                                        <form action="{{ route('subtasks.toggle-done', $subtask->id) }}" method="POST">
                                            @csrf
                                            @method('put')
                                            @if($subtask->status == 'belum')
                                            <input type="hidden" name="modal_origin" value="subtaskViewModal">
                                            <button class="btn btn-success btn-sm" title="Selesai">
                                                ✔
                                            </button>
                                            @endif
                                        </form>

                                        <!-- Delete Subtask -->
                                        <form action="{{ route('subtasks.delete', $subtask->id) }}" id="formHapusSub{{ $subtask->id }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <input type="hidden" name="modal_origin" value="subtaskViewModal">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus?')">✕</button>
                                        </form>
                                    </div>
                                </li>
                                @endforeach
                                @endif
                            </ul>

                        </div>

                    </div>
                </div>
            </div>



            {{-- edit subtugas --}}
            @foreach ($task->subtasks as $subtask)
            <div class="modal fade" id="editSubtaskModal-{{ $subtask->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTaskModalLabel">Edit subtask - {{ $subtask->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body">
                            <form action="{{ route('subtasks.edit', $subtask->id) }}" id="formEditSubtugas{{ $subtask->id }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Sub task</label>
                                    <input type="text" class="form-control" name="name" value="{{ $subtask->name }}" required>
                                </div>
            
                                <div class="mb-3">
                                    <label for="tenggat_waktu" class="form-label">Tenggat Waktu</label>
                                    <input type="datetime-local" class="form-control" name="tenggat_waktu" oninput="this.blur()" required value="{{ \Carbon\Carbon::parse($subtask->tenggat_waktu)->format('Y-m-d\TH:i') }}">
                                </div>
            
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Yakin ingin mengedit?')">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            






            @endforeach
            @endif
        </ul>

    <!-- Pagination -->
    <div class="mt-3 flex justify-center">
        <ul class="pagination">
            {{-- Previous Button --}}
            @if ($tasks->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">«</span>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $tasks->previousPageUrl() }}" class="page-link">«</a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach(range(1, $tasks->lastPage()) as $i)
                @if ($i == 1 || $i == $tasks->lastPage() || ($i >= $tasks->currentPage() - 2 && $i <= $tasks->currentPage() + 2))
                <li class="page-item {{ $i == $tasks->currentPage() ? 'active' : '' }}">
                    <a href="{{ $tasks->url($i) }}" class="page-link">
                        {{ $i }}
                    </a>
                </li>            
                @elseif ($i == 2 || $i == $tasks->lastPage() - 1)
                    <li class="page-item">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endforeach

            {{-- Next Button --}}
            @if ($tasks->hasMorePages())
                <li class="page-item">
                    <a href="{{ $tasks->nextPageUrl() }}" class="page-link">»</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">»</span>
                </li>
            @endif
        </ul>
    </div>





    </div>
</div>

        </div>
    </div>
</div>



@if (session('reminders_shown') != true && $reminders->count() > 0)
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let reminderTasks = {!! json_encode($reminders->pluck('name')->toArray()) !!};
            let taskList = reminderTasks.join('<br>• ');

            Swal.fire({
                title: 'Reminder!',
                html: 'Tugas berikut sudah waktunya dikerjakan:<br><br>• ' + taskList,
                icon: 'info',
                confirmButtonText: 'Oke'
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    fetch("{{ route('tasks.updateReminderStatus') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            task_ids: {!! json_encode($reminders->pluck('id')->toArray()) !!}
                        })
                    }).then(response => {
                        console.log('Reminder status updated');
                    }).catch(error => {
                        console.error('Error:', error);
                    });
                }
            });
        });
    </script>
@endif



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    document.querySelectorAll('.alert').forEach(function (alert) {
        alert.style.cursor = 'pointer';
        alert.addEventListener('click', function () {
            alert.style.display = 'none'; 
        });
    });

    function munculFormTask(forceOpen = false) {
        const cardBody = document.querySelector('.card-body');
        if (forceOpen) {
            cardBody.style.display = 'block';
            setTimeout(() => {
                cardBody.style.opacity = '1';
            }, 10);
            return;
        }

        if (cardBody.style.display === 'none' || cardBody.style.display === '') {
            cardBody.style.display = 'block';
            setTimeout(() => {
                cardBody.style.opacity = '1';
            }, 10);
        } else {
            cardBody.style.opacity = '0';
            setTimeout(() => {
                cardBody.style.display = 'none';
            }, 300);
        }
    }


    document.addEventListener("DOMContentLoaded", function () {
        if (sessionStorage.getItem("showForm") === "true") {
            munculFormTask(true); 
            sessionStorage.removeItem("showForm"); 
        }
    });


    

    


    
</script>

<script>
 
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('SopenSubtaskModal'))
            var taskId = "{{ session('SopenSubtaskModal') }}"; 
            var subtaskModal = new bootstrap.Modal(document.getElementById("subtaskModal-" + taskId));
            subtaskModal.show();
        @endif
    });

    document.addEventListener("DOMContentLoaded", function() {
    let openModal = "{{ session('openSubtaskModal') }}"; 
    
    if (openModal) {
        let modalParts = openModal.split("-");
        let modalId = modalParts[0];  
        let taskId = modalParts[1];   

        let targetModal = document.getElementById(modalId + "-" + taskId);
        if (targetModal) {
            let modalInstance = new bootstrap.Modal(targetModal);
            modalInstance.show();
        }
    }
});



function hapusAll(){
    if(confirm("Apa anda yakin ingin menghapus semua tugas dan subtugas yang sudah selesai ?")){
        document.getElementById('formHapusAll').submit();
    }
}

// function hapusTugas(tipe, id){
//     let formID = "";

//     if(tipe === "tugas"){
//         formID = "formHapus" + id;
//     } else if(tipe === "subtugas"){
//         formID = "formHapusSub" + id;
//     }

//     if(formID && confirm("Yakin ingin menghapus ?")) {
//         const form = document.getElementById(formID);
//         if(form){
//             form.submit()
//         }else{
//             console.error("Form tidak ditemukan", formID)
//         }
//     }
   
// }


// function tambahTugas(){
//     if(confirm("Apa semua data sudah benar ?")){
//         document.getElementById('tambahTugas').submit();
//         console.log("tes");
//     }
// }


// function edit(tipe, id){
//     let formID = "";
//     console.log("fungsi berhasil di jalankan dengan tipe", tipe, id);

//     if(tipe === "tugas"){
//         formID = "formEditTugas" + id;
//     } else if(tipe === "subtugas"){
//         formID = "formEditSubtugas" + id;
//     }

//     if(formID && confirm("Apa anda yakin ingin mengubah ?")) {
//         const form = document.getElementById(formID);
//         if(form){
//             form.submit()
//         }else{
//             console.error("Form tidak ditemukan", formID)
//         }
//     }
// }


</script>


{{-- <script>
    const h3 = document.querySelector('h3');
    const cardBody = document.querySelector('.card-body');

    h3.addEventListener('click', () => {
        if (cardBody.style.display === 'none' || cardBody.style.display === '') {
            cardBody.style.display = 'block'; 
            setTimeout(() => {
                cardBody.style.opacity = '1'; 
            }, 10); 
        } else {
            cardBody.style.opacity = '0'; 
            setTimeout(() => {
                cardBody.style.display = 'none'; 
            }, 300); 
        }
    });
</script> --}}

</body>
</html>