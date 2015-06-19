@extends('app')

@section('content')

<ul class="cbp_tmtimeline">
	<li>
		<div class="cbp_tmicon">
			<img src="{{ $providerUser->avatar }}" class="img-responsive img-circle" />
		</div>
		<div class="cbp_tmlabel header">
			<ul class="list-inline">
				<li>
					<h3>{{ $providerUser->name }}</h3>
					<span>{{ $providerUser->email or trans('app.noemail') }}</span>
				</li>
			</ul>
		</div>
	</li>
	<li>
		<div class="cbp_tmlabel header">
			<h2>
				<i class="{{ config("adsocial.actions.$provider.icon") }}"></i>
				{{ ucfirst($provider) }}
			</h2>
		</div>
	</li>
	@foreach($feed as $item)
	<li>
		<time class="cbp_tmtime" title="{{ $item->posted_at->format('d/m H:i') }}">
			<span>{{ $item->posted_at->diffForHumans() }}</span>
		</time>
		<div class="cbp_tmlabel">
			<div class="row">
				<div class="col-sm-9">
					<a href="{{ $item->link }}">
						<h3>{{ $item->text }}</h3>
					</a>
				</div>
				@if($item->image)
				<div class="col-sm-3" style="text-align: right;">
					<img src="{{ $item->image }}">
				</div>
				@endif
			</div>
			<a class="btn btn-icon icon-left {{ $item->market->count() ? 'btn-primary' : 'btn-green' }}"
				data-toggle="modal" data-target="#{{ $provider }}BoostModal" data-post-id="{{ $item->id }}">
				<i class="fa {{ $item->market->count() ? 'fa-pencil' : 'fa-plus' }}"></i>
				{{ trans($item->market->count() ? 'app.boosted' : 'app.boost') }}
			</a>
		</div>
	</li>
	@endforeach
</ul>

@include('modals.boost', compact('provider'))

@endsection

@section('js')
$(function() {
	var modal = $('#{{ $provider }}BoostModal')
	modal.find('.submit.btn').on('click', function() {
		$.ajax({
			method: 'POST',
			url: '/api/v1/trade/boost',
			data: modal.find('form').serialize(),
			complete: function(){ window.location.reload() }
		})
	})

	$('[data-post-id]').click(function() {
		modal.find('[name="post_id"]').val($(this).data('post-id'))
		modal.find('.post-preview').html($(this).prev().html())
	})
})
@stop