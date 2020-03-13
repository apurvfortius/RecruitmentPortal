<table class="table table-bordered">
    <thead>                  
        <tr>
            @foreach($listing_label as $label)
                <th> {{ $label }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody id="search_body">
        @foreach($result->data as $item)
            <tr>
                @foreach($this->listing_cols as $column)
                    @if($column == 'id'){
                        <td>
                            <input class='custom-control-input' id='assignbox' name='assignbox[{{ $item->$column }}]' type='checkbox' id='customCheckbox1' value='{{ $item->$column }}'> 
                            <label for='customCheckbox1' class='custom-control-label'>{{ $item->$column }}</label>
                        </td>";
                    }
                    @else{
                        <td>{{ $item->$column }}</td>
                    }
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
    {{-- <tfoot>

    </tfoot> --}}
</table>