<p>@lang('lang.are_you_sure_to_delete')</p>
<div class="modal-footer compareFooter deleteButtonDiv">
    <button type="button" class="modalbtn btn-primary"><a href="{{route('delete-supplier',$id)}}"
            class="text-light">@lang('lang.yes')</a></button>
    <button type="button" class="modalbtn btn-danger" data-dismiss="modal">@lang('lang.none')</button>
</div>