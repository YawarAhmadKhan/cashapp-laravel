<div class="col-xl-12">
    <!-- Area Chart -->
    
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Transaction Lists</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                {{-- <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div> --}}
                <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                    <div class="w-full overflow-x-auto table-responsive">
                      <table class="w-full whitespace-no-wrap">
                        <thead>
                          <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th>#No</th>
                            <th class="px-4 py-3">Transaction-Type</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Description</th>
                            {{-- <th class="px-4 py-3">BtcDetails</th> --}}

                          </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @forelse ($list as $item)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-sm">
                                    {{$loop->iteration}}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{$item->transaction_type}}
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
                                    {{Str::after($item->notes,' ')}}
                                </td>
                                {{-- <td class="px-4 py-3 text-sm">
                                    {{ Str::limit($item->btcdetails,50)}}
                                </td> --}}
                              </tr>
                            @empty
                                <tr>
                                    <td>Zero Transactions</td>
                                </tr>
                            @endforelse 
                        </tbody>
                      </table>
                      {{ $list->links(data: ['scrollTo' => false])}}
                    </div>
                  </div>
                 
              
            </div>    
        </div>
  
</div>
{{-- @preserveScroll --}}