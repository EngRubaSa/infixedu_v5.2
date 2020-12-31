<script src="{{asset('public/backEnd/')}}/js/main.js"></script>
        <div class="add-visitor">
            <div class="common-fields" id="common-fields">

                <div class="row  mt-25">
                    <div class="col-lg-12">
                        <div class="input-effect">
                            <input class="primary-input form-control"
                                type="number" name="mark" autocomplete="off" value="{{@$question_bank->marks}}" required>
                            <label>@lang('lang.marks')</label>
                            <span class="focus-border"></span>
                        </div>
                    </div>
                </div>
                <div class="row mt-25">
                    <div class="col-lg-12">
                        <div class="input-effect">
                            <textarea class="primary-input form-control" cols="0" rows="5" name="question_title" readonly="true">{{@$question_bank->question}}</textarea>
                            <label>@lang('lang.question') @lang('lang.title')</label>
                            <span class="focus-border textarea"></span>
                        </div>
                    </div>
                </div>
            </div>
            @if(@$question_bank->type == "M")
            @php
                $multiple_options = $question_bank->questionMu;
                $number_of_option = $question_bank->questionMu->count();
            @endphp
            <div class="multiple-choice" id="multiple-choice">
                
                <div class="row  mt-25">
                    <div class="col-lg-10">
                        <div class="input-effect">
                            <input class="primary-input form-control"
                                type="number" name="number_of_option" autocomplete="off" id="number_of_option_edit" value="{{@$number_of_option}}">
                            <label>@lang('lang.number_options')</label>
                            <span class="focus-border"></span>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        {{-- <button class="primary-btn fix-gr-bg" type="button" id="create-option-edit">Create</button> --}}
                    </div>
                </div>
            </div>

            <div class="multiple-options" id="multiple-options">
                @php $i=0; @endphp
                @foreach($multiple_options as $multiple_option)
                @php $i++; @endphp
                <div class='row  mt-25'>
                    <div class='col-lg-10'>
                        <div class='input-effect'>
                            <input class='primary-input form-control' type='text' name='option[]' autocomplete='off' required value="{{@$multiple_option->title}}">
                            <label>@lang('lang.option') {{$i}}</label>
                            <span class='focus-border'></span>
                        </div>
                    </div>
                    <div class='col-lg-2'>
                        <input type='checkbox' class="common-checkbox" id="commonCheckbox{{$i}}" name='option_check_{{$i}}' value='1' {{@$multiple_option->status == 1? 'checked': ''}} disabled="">
                        <label for="commonCheckbox{{$i}}"></label>
                    </div>
                </div>
                @endforeach

            </div>
            @elseif($question_bank->type == "T")
            <div class="true-false" id="true-false">
                <div class="row  mt-25">
                    <div class="col-lg-12 d-flex">
                        <p class="text-uppercase fw-500 mb-10"></p>
                        <div class="d-flex radio-btn-flex">
                            <div class="mr-30">
                                <input type="radio" name="trueOrFalse" id="relationFatherEdit" value="T" class="common-radio relationButton" {{@$question_bank->trueFalse == 'T'? 'checked': ''}} disabled="">
                                <label for="relationFatherEdit">@lang('lang.true')</label>
                            </div>
                            <div class="mr-30">
                                <input type="radio" name="trueOrFalse" id="relationMotherEdit" value="F" class="common-radio relationButton" {{@$question_bank->trueFalse == 'F'? 'checked': ''}} disabled="">
                                <label for="relationMotherEdit">@lang('lang.false')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="fill-in-the-blanks" id="fill-in-the-blanks">
                <div class="row  mt-25">
                    <div class="col-lg-12">
                        <div class="input-effect">
                            <textarea class="primary-input form-control" cols="0" rows="5" name="suitable_words" readonly="true">{{@$question_bank->suitable_words}}</textarea>
                            <label>@lang('lang.suitable_words')</label>
                            <span class="focus-border textarea"></span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>


