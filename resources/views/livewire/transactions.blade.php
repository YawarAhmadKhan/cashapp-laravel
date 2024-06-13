<div>
   <div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4
            class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300" >
            Table with actions
          </h4>
          <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <!-- Button trigger modal -->
{{-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
  Launch demo modal
</button> --}}


            <div class="w-full overflow-x-auto table-responsive">
                <table class="table">
                    <thead class="table-light">
                      <tr>
                        <th>No.</th>
                        <th>Recipient</th>
                        <th class="text-xs">amount</th>
                        <th class="text-xs">identifier</th>
                        <th class="text-xs">From</th>
                        <th class="text-xs">Status</th>
                        <th class="text-xs">Refund-note</th>
                        <th class="text-xs">Ref-amount</th>
                        <th class="text-xs">App</th>
                        <th class="text-xs">Subject</th>
                        <th class="text-xs">TransactionDate</th>
                        <th class="text-xs" colspan="2">Actions</th>

                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($data as $item)
                      <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->recipient}}</td>
                        <td>{{$item->amount}}</td>
                        <td>{{$item->identifier}}</td>
                        <td>{{$item->from}}</td>
                        <td>{{$item->status}}</td>
                        <td>{{$item->refundnote}}</td>
                        <td>{{$item->refundamount}}</td>
                        <td>{{Str::limit($item->app,4)}}</td>
                        <td>{{Str::after($item->subject, ' ') }}</td>
                        <td>{{$item->date}}</td>
                        <td class="d-flex justify-content-between">
                          <button class="btn" wire:click="edit({{$item->id}})"><i class="fas fa-user-edit text-success"></i></button>
                          <button class="btn" onclick="deleteRec({{$item->id}})"><i class="fas fa-trash text-danger"></i></button>
                        </td>
                      </tr>
                      @empty
                          <tr>
                            <td>Zero Data Found For Selected Email</td>
                          </tr>
                      @endforelse
                      
                    </tbody>
                  </table>
                  {{ $data->links(data: ['scrollTo' => false])}}
            </div>
          </div>
        </div>
    </div>
   </div>
 
<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label>Amount</label>
            <input type="number" class="form-control {{ $errors->has('amount') ? 'is-invalid': (strlen($amount > 0) ? 'is-valid': '')}}" name="amount" value="{{$amount}}" wire:model="amount">
          </div>
          <div class="form-group">
            <label>Subject</label>
            <input type="text" class="form-control {{ $errors->has('subject') ? 'is-invalid': (strlen($subject > 0) ? 'is-valid': '')}}" name="subject" value="{{$subject}}" wire:model="subject">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" wire:click="update()">Save changes</button>
      </div>
    </div>
  </div>
</div>
@push('editModal')
<script>
  $(document).ready(function() {
    $(window).on('openModal', function() {
      $('#exampleModalCenter').modal('show');
    });
    $(window).on('closeModal', function() {
      $('#exampleModalCenter').modal('hide');
    });
  });
  function deleteRec(id){
        Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(id);
                    Swal.fire(
      'Deleted!',
      'Your file has been deleted.',
      'success'
    )
                }
            });

    }
</script>
@endpush

</div>
