@extends('layouts.adminmain')

@section('content')

<section class="section">  
  <div class="section-header">
    <h1>Ruangan <small>Add Data</small></h1>
  </div>

  <div class="section-body">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="card">
          <div class="card-header">
            <a href="{{ route('ruangan.index') }}"> 
              <button type="button" class="btn btn-outline-info">
                <i class="fas fa-arrow-circle-left"></i> Back
              </button>
            </a>
          </div>

          @if (count($errors) > 0)
            <div class="card col-lg-6">
                <div class="card-body">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
          @endif
          
          <div class="card-body">
            <form action="{{ route('ruangan.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control">
              </div>
              <div class="form-group">
                  <label>Jurusan</label>
                  <select class="form-control" name="jurusan_id">
                    @foreach($data as $jurusan)
                      <option value="{{ $jurusan->id }}">{{ $jurusan->name }}</option>
                    @endforeach
                  </select>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary">SAVE</button>
              </div>
              </form>
          </div>
        </div>
      </div>  
  </div>
</section>

@endsection