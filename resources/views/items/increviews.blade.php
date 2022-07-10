<?php 
if (isset($review_id))
  $review=App\Review::all()->where('id',$review_id)->first(); 
  ?>

@if (($item->active_reviews->count()>0) && (($review_option=='showall') || ($review_option=='create')))
<h2>aktywne przeglądy</h2>
<ol>
@foreach ($item->active_reviews as $review_one)
    <li>
    {{$review_one->start_date}}: <strong>{{$review_one->review_title}}</strong>
        [<a href="{{ route('review.show', $review_one->id) }}">pokaż</a>]
    </li>
@endforeach
</ol>
@endif



@switch ($review_option)
    @case ('show')
    <hr>
    <div class="row">
    <div class="col-sm-12">
    
    <h4>szczegóły przeglądu [{{$review->id}}]  </h4>
    
    </div>
    </div>
    <div class="row bg-primary">
    <div class="col-sm-2">
        planowana data przeglądu:<br>
    {{$review->start_date}}
    </div>
    <div class="col-sm-2">
        zakres:<br>
    {{$review->start_date_from}} - {{$review->start_date_to}}
    </div>
    <div class="col-sm-2">
        rodzaj:<br>
        {{$review->template->typ()}}
    </div>
    <div class="col-sm-2">
        @if ($review->rev_status!=100)
        <a href="{{ route('review.edit', $review->id) }}"><span class="bg-info glyphicon glyphicon-edit glyphiconbig pull-right"></span></a>
        @endif
        status:<br>
    {{$review->status()}}
    </div>
        @if ($review->rev_status==100)
        <div class="col-sm-2">
            zamykający:<br>
            {{$review->reviewer()->full_name()}}
        </div>
        <div class="col-sm-2">
            data zamknięcia:<br>
            {{$review->do_date}}
        
        </div>
        @else
        <a href="{{ route('review.tryclose', $review->id) }}"><span class="bg-info glyphicon glyphicon-off glyphiconbig"></span></a>
        @endif
    </div>
    <div class="row bg-info">
        <div class="col-sm-12">
            <h3>{{$review->review_title}}</h3>
            {!!$review->template->template_body!!}
            <br><br>
        </div>
        <div class="col-sm-12  bg-primary">
            <h4>raport z przeglądu:</h3>
            {!!$review->review_body!!}
            <br><br>
        </div>
    </div>

    <hr>
    @break


    @case ('edit')
    <hr>

    <div class="row bg-info">
        <div class="col-sm-12">
            <h3>{{$review->review_title}}</h3>
            {!!$review->template->template_body!!}
            <br><br>
        </div>
    </div>
    <form method="post" action="{{ route('review.update') }}">
        <input type="hidden" name="_method" value="PUT">
        {{ csrf_field() }}
        <h2>raport z przeglądu [{{$review->id}}]</h2>
        <fieldset>
            <label for="review_body">treść raportu:</label><br>
            <textarea id="review_body" name="review_body" rows="4" cols="150">{!!$review->review_body!!}</textarea>
        </fieldset>
    <div class="modal-footer">
        <input type="hidden" name="review_id" value="{{$review_id}}">
        <a href="{{ url()->previous() }}">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
        </a>
        <button type="submit" class="btn btn-primary">zapisz</button>
    </div>
    </form>
    <hr>
    @break



    @case ('tryclose')

        <hr>
        <h2>zamknięcie przeglądu </h2>
        <div class="row bg-info">
            <div class="col-sm-12">
                <h3>{{$review->review_title}}</h3>
                {!!$review->template->template_body!!}
                <br><br>
            </div>
            <div class="col-sm-12  bg-primary">
                <h4>raport z przeglądu:</h3>
                {!!$review->review_body!!}
                <br><br>
            </div>
        </div>

        <div class="row bg-info">
            <div class="col-sm-12">

                <form method="post" action="{{ route('review.close') }}">
                    <input type="hidden" name="_method" value="PUT">
                    {{ csrf_field() }}
                    
                    <label for="review_template_id">wybierz kolejny przegląd do realizacji:</label>
                    <br>
                    <select class="form-control form-select" id="review_template_id" name="review_template_id" size="1">
                    @foreach ($item->group()->review_choose($review->template->review_type) as $review_template)
                        <?php
                        $date_a=$review_template->next_start();
                        $date_b=$review_template->next_start()+$review_template->days_before;
                        $date_c=$review_template->next_start()+$review_template->days_before+$review_template->days_after;   
                        ?>
                        <option value="{{$review_template->id}}">
                            {{$review_template->template_title}}: {{date('Y-m-d',strtotime("+$date_b day"))}} ({{date('Y-m-d',strtotime("+$date_a day"))}} - {{date('Y-m-d',strtotime("+$date_c day"))}})
                        </option>
                    @endforeach
                    </select>

                    @if (!$review->template->is_userable())
                    <select class="form-control form-select" id="rev_status" name="rev_status" size="1">
                        <option value="1">do zaplanowania</option>
                        <option value="2">zaplanowany</option>
                    </select>
                    @endif


                    <div class="modal-footer">
                    <input type="hidden" name="review_id" value="{{$review_id}}">
                    <a href="{{ url()->previous() }}">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
                    </a>
                    <button type="submit" class="btn btn-primary">zamknij przegląd</button>
                    </div>
                </form>
            </div>
        </div>
  <hr>
@break

@endswitch
<?php /*
@case ('create')

<hr>
<h2>nowy przegląd </h2>

<form method="post" action="{{ route('review.store') }}">
    <!--input type="hidden" name="_method" value="PUT"-->
    {{ csrf_field() }}

    <fieldset>
        <label for="fault_title">tytuł:</label>
        <input type="text" id="fault_title" name="fault_title"><br><br>
        <label for="notification_description">opis:</label>
        <textarea id="notification_description" name="notification_description" rows="4" cols="150"></textarea>
    </fieldset>


  <div class="modal-footer">
    <input type="hidden" name="item_id" value="{{$item->id}}">
    <a href="{{ url()->previous() }}">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">anuluj</button>
    </a>
    <button type="submit" class="btn btn-primary">zapisz</button>
  </div>
  </form>
@break








@endswitch

@if (($item->close_faults->count()>0) && ($review_option=='showall'))
<h2>złoszenia zamkniete </h2>
<ol>
@foreach ($item->close_faults as $review_one)
    <li>
        {{$review_one->fault_title}}
        [<a href="{{ route('fault.show', $review_one->id) }}">pokaż</a>]
    </li>
@endforeach
</ol>
@endif

*/ ?>