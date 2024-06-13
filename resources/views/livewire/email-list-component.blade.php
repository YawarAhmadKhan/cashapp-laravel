<div>

    <!-- Button trigger modal -->
    <div class="row">
        <div class="col-md-12 d-flex justify-content-end m-1">
            <button type="button" class="btn btn-primary text-center" data-toggle="modal" data-target="#exampleModal">
                Click To Add Emial
              </button>
              
        </div>
       
        <div class="col-md-12">
               <table class="table">
                <thead>
                    <tr>
                        <th>#No</th>
                        <th>Email</th>
                        <th>Filter</th>
                        <th>Actions</th>
    
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->filteremail}}</td>
                        <td>
                    <button class="btn" wire:click="edit({{$item->id}})"><img src="{{asset('3597075.png')}}"alt="edit icon"class="img-fluid" style="width: 25px"></button>
                    <button class="btn" onclick="deleteItem({{ $item->id }})"><img src="{{asset('delete.jpg')}}"alt="edit icon"class="img-fluid" style="width: 25px"></td></button>
        
                    </tr>
                    @empty
                        <tr>
                            <td>No Emails here </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        
    </div>
    
    
    
    
     
    
      <!-- Modal -->
      <div class="modal fade" wire:ignore.self  id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"> <img src="{{asset('Add Email(2).png')}}"class="img-fluid"style="width: 35px" alt="icone"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="col-lg-12 d-flex align-items-start ">
                    
           
                    {{-- <form wire:submit.prevent="default"> --}}
            <div class="col-lg-6 ">  
                <label>Email</label>       
            <input type="email" wire:model.live="email" placeholder="Your Email" class="form-control {{ $errors->has('email') ? 'is-invalid' : (strlen($email > 0) ? 'is-valid' :'')}}">
            @error('email')
            <p class="invalid-feedback">{{ $message }}</p>
            @enderror
            
            </div>
            <div class="col-lg-6 "> 
                <label>Filter</label>
            <input type="email" wire:model.live="filteremail" placeholder="Enter Filter" class="form-control {{ $errors->has('filteremail') ? 'is-invalid' : (strlen($filteremail>0) ? 'is-valid' :'')}}">
            @error('filteremail')
                  <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            {{-- </form> --}}      
                    
                   </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" wire:click="store()">{{$BtnText}}</button>
            </div>
          </div>
        </div>
      </div>
      <script>
        function deleteItem(itemId) {
            var id = itemId;
            if(confirm('are you sure want to delete')){
                @this.delete(id);
            };
            
          console.log(id);
        }
        document.addEventListener('closemodel', event => {
            $('#exampleModal').modal('hide');    });
            document.addEventListener('openmodel', event => {
                $('#exampleModal').modal('show');    });
                
      </script>
    
    </div>
    
    
    
    
      