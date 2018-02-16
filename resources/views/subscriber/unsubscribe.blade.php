@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($success)
            <div class="alert alert-success" role="alert">
                You have successfully unsibscribed from the integration hook: {{ $integration }}:{{ $hook }}.
            </div>
        @endif
        @if (!$errors->any() && !$success)
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form method="POST" action="{{ route('unsubscribe', ['hash' => base64_encode(json_encode(['email' => $email, 'integration' => $integration, 'hook' => $hook]))]) }}">
                        @csrf
                        <div class="unsubscribe-modal modal fade">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Unsubscriber Service</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to unsubscribe from the below service hook?</p>
                                        <p>
                                            Integration: <span class="font-weight-bold">{{ $integration }}</span><br />
                                            Hook: <span class="font-weight-bold">{{ $hook }}</span>
                                        </p>
                                        <p>
                                            <span class="text-info"><i class="fa fa-info-circle"></i> This means you will no longer receive notfication when you are mentioned in a comment on Jira.</span>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Confirm</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('footer-js')
    <script type="text/javascript">
        @if(isset($consoleMessage))
            console.log('%c{{ $consoleMessage }}', 'background: #222; color: #bada55');
        @endif

        $(window).on('load',function(){
            $('.unsubscribe-modal').modal('show');
        });
    </script>
@endsection
