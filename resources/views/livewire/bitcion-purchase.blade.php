<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <div>
                        <h6 class="m-0 font-weight-bold text-primary">Bitcion Purchased List</h6>
                        <p class="text-muted"> Total Bitcion Purchased <span class="text-bold text-danger">${{$BtcPurchased}}</span></p>
                        </div>
                        
                        <div class="dropdown no-arrow">
                            <form
        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" class="form-control bg-dark text-white border-0 small" placeholder="Search for..."
                aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-filter  "></i>
                </button>
            </div>
        </div>
    </form>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        {{-- <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div> --}}
                        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                            <div class="w-full overflow-x-auto table-responsive">
                              <table class="w-full whitespace-no-wrap table-hover" style="width: 100%">
                                <thead>
                                  <tr
                                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                    <th>#No</th>
                                    <th class="px-4 py-3">Transaction-Type</th>
                                    <th class="px-4 py-3">Amount</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Description</th>
                                    <th>Date</th>
                                    {{-- <th class="px-4 py-3">BtcDetails</th> --}}
        
                                  </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                    @forelse ($data as $item)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-sm">
                                            {{$loop->iteration}}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{$item->payment_note}}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            {{$item->amount}}
                                        </td>
                                        @if ($item->status == 'Completed')
                                        
                                        <td class="px-4 py-3  text-success">
                        
                                          {{$item->status}} 
                                        </td>
                                        @elseif($item->status == 'Received')
                                        <td class="px-4 py-3  text-info">
                                        {{$item->status}}
                                        </td>
                                        @else
                                        <td class="px-4 py-3  text-danger">
                                            {{$item->status}}
                                            </td>
                                        @endif
                                        <td class="px-4 py-3 text-sm">
                                            {{Str::after($item->subject,' ')}}
                                        </td>
                                        {{-- <td class="px-4 py-3 text-sm">
                                            {{ Str::limit($item->btcdetails,50)}}
                                        </td> --}}
                                        <td class=" text-sm">
                                            {{ $item->date}}
                                        </td>
                                      </tr>
                                    @empty
                                        <tr>
                                            <td>Zero Bitcion Purchased</td>
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
        </div>
    </div>
</div>
