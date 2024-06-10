<div>
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        {{-- <div class="col-xl-3 col-md-6 mb-4 ">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                 (CAsh App)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 ">$ {{$totalAmount}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tablet-alt fa-2x text-success"></i>
                         
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        
        <div class="col-xl-3 col-md-6 mb-4 ">
            <div class="card border-left-primary shadow h-100 py-2">
                <a href="{{route('receive-amount')}}" style="text-decoration: none" wire:navigate>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Amount Recieve</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 ">$ {{$totalAmount}}</div>
                        </div>
                        <div class="col-auto">
                            {{-- <i class="fas fa-hand-holding-usd fa-2x text-primary"></i> --}}
                            <i class=""><img class="img-fluid" src="{{asset('5303784.png')}}" style="width: 45px"></i>

                            
                            
                        </div>
                    </div>
                </div>
            </a>
            </div>
        </div>
   
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <a href="{{route('sent-amount')}}" style="text-decoration: none"wire:navigate>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Send (Cash)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$ {{$totalSent}}</div>
                        </div>
                        <div class="col-auto">
                            {{-- <i class="fas fa-paper-plane fa-2x text-success"></i> --}}
                            <i class=""><img class="img-fluid" src="{{asset('sent2.png')}}" style="width: 45px"></i>

                           
                        </div>
                    </div>
                </div>
                </a>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <a href="{{route('refunds')}}" style="text-decoration: none"wire:navigate>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Refunds
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">$ {{$cashRefunded}}</div>
                                </div>
                                {{-- <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-auto">
                            {{-- <i class="fas fa-wallet  fa-2x text-info"></i> --}}
                            <i class=""><img class="img-fluid" src="{{asset('1407.png')}}" style="width: 45px"></i>

                        </div>
                    </div>
                </div>
                </a>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <a href="{{route('bitcionpurchase')}}" style="text-decoration: none"wire:navigate>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Bitcion Purchased Order</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$ {{$BtcPurchased}}</div>
                        </div>
                        <div class="col-auto">
                            {{-- <i class="fab fa-btc fa-2x text-warning"></i> --}}
                            <i class=""><img class="img-fluid" src="{{asset('bit1.jpeg')}}" style="width: 45px"></i>

                        </div>
                    </div>
                </div>
                </a>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <a href="{{route('bitcionsell')}}" style="text-decoration: none" wire:navigate>
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Btc Sell {{$Btcsell}} - ( platform fee {{$Fee}})</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$ {{$Btcamount}}</div>
                           
                        </div>
                        <div class="col-auto">
                            {{-- <i class="fab fa-btc fa-2x text-danger"></i> --}}
                            <i class=""><img class="img-fluid" src="{{asset('bit1.jpeg')}}" style="width: 45px"></i>

                        </div>
                    </div>
                </div>
                </a>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                               Current Amount In Cash App</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$ {{$cash}}</div>
                           
                        </div>
                        <div class="col-auto">
                            <i class=""><img class="img-fluid" src="{{asset('images.png')}}" style="width: 60px"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                               Disputed Payment</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$ {{$disputeAmounts}}</div>
                           
                        </div>
                        <div class="col-auto">
                            <i class=""><img class="img-fluid" src="{{asset('argue.png')}}" style="width: 45px"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                               Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$ {{$disputeAmounts}}</div>
                           
                        </div>
                        <div class="col-auto">
                            <i class=""><img class="img-fluid" src="{{asset('argue.png')}}" style="width: 45px"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Content Row -->
</div>
