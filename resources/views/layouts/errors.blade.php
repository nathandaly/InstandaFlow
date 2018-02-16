@if ($errors->any())
    <div id="accordion" role="tablist" aria-multiselectable="true">
        <?php $allErrors = $errors->toArray(); ?>

        @foreach ($allErrors as $key => $error)
                <?php $error = reset($error); ?>
                <div class="card text-white bg-danger rounded-0">
                    <div class="card-header text-white" id="headingOne">
                        <div class="container">
                            <h5 class="mb-0">
                                <button class="btn btn-link text-white collapsed" data-toggle="collapse" data-target="#collapse{{ $key }}" aria-expanded="true" aria-controls="collapse{{ $key }}">
                                    {{ isset($error['message']) ? $error['message'] : $error }}
                                </button>
                            </h5>
                        </div>
                    </div>
                    @if (isset($error['meta']))
                        <div id="collapse{{ $key }}" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="container">
                                <div class="card-body">
                                    <p>
                                        @foreach ($error['meta'] as $meta)
                                            {{ $meta }} <br />
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endif