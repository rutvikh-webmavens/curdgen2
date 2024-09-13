@can('menu_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.menus.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.menu.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.menu.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-projectMenus">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.project') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.model_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.parent') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.sort_order') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.soft_delete') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.create') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.edit') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.show') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.delete') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.column_search') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.entries_per_page') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.order_by_field_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.menu.fields.order_by_desc') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $key => $menu)
                        <tr data-entry-id="{{ $menu->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $menu->id ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Menu::TYPE_SELECT[$menu->type] ?? '' }}
                            </td>
                            <td>
                                {{ $menu->project->name ?? '' }}
                            </td>
                            <td>
                                {{ $menu->model_name ?? '' }}
                            </td>
                            <td>
                                {{ $menu->title ?? '' }}
                            </td>
                            <td>
                                {{ $menu->parent->model_name ?? '' }}
                            </td>
                            <td>
                                {{ $menu->sort_order ?? '' }}
                            </td>
                            <td>
                                <span style="display:none">{{ $menu->soft_delete ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $menu->soft_delete ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $menu->create ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $menu->create ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $menu->edit ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $menu->edit ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $menu->show ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $menu->show ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $menu->delete ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $menu->delete ? 'checked' : '' }}>
                            </td>
                            <td>
                                <span style="display:none">{{ $menu->column_search ?? '' }}</span>
                                <input type="checkbox" disabled="disabled" {{ $menu->column_search ? 'checked' : '' }}>
                            </td>
                            <td>
                                {{ App\Models\Menu::ENTRIES_PER_PAGE_SELECT[$menu->entries_per_page] ?? '' }}
                            </td>
                            <td>
                                {{ $menu->order_by_field_name ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Menu::ORDER_BY_DESC_SELECT[$menu->order_by_desc] ?? '' }}
                            </td>
                            <td>
                                @can('menu_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.menus.show', $menu->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('menu_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.menus.edit', $menu->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('menu_delete')
                                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('menu_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.menus.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-projectMenus:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection