<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Product Type</th>
                <th>Duration in Months (or doses for ESWT)</th>
                <th>Price</th>
                <th>Description</th>
                <th>Note</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="products-rows" data-token="{{ csrf_token() }}" data-product-types='{{ json_encode($product_types) }}'>

            @foreach ($products as $p)
                <form action="" id="form-{{ $p->id }}">
                    <tr id="{{ $p->id }}" data-delete="{{ route('product.delete', ['product' => $p->id]) }}" data-restore="{{ route('product.restore', ['product' => $p->id]) }}"
                        data-update="{{ route('product.update', ['product' => $p->id]) }}">
                        <td class="editable">
                            <input class="form-control text" value="{{ $p->name }}" id="name-{{ $p->id }}" name="name-{{ $p->id }}" data-original-value="{{ $p->name }}" readonly />
                        </td>
                        <td>
                            <select class="product_type_select form-control" id="product_type_select-{{ $p->id }}" data-original-value="{{ $p->product_type_id }}" disabled>
                                @if (!$p->product_type_id)
                                    <option value="0" name="Not Set" value="Not Set">Not Set</option>
                                @endif
                                @foreach ($product_types as $type)
                                    <option id="product_type_{{ $type->id }}" value="{{ $type->id }}" name="{{ $type->description }}" {{ $type->id === $p->product_type_id ? 'selected' : '' }}>
                                        {{ $type->description }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="editable">
                            <input class="form-control numeric" value="{{ $p->amount }}" id="amount-{{ $p->id }}" name="amount-{{ $p->id }}" data-original-value="{{ $p->amount }}" readonly />
                        </td>
                        <td class="editable">
                            <input class="form-control currency" value="${{ number_format($p->price, 2, '.', '') }}" name="price-{{ $p->id }}" id="price-{{ $p->id }}" data-original-value="{{ $p->price }}" readonly />
                        </td>
                        <td class="editable">
                            <input class="form-control text" value="{{ $p->description }}" id="description-{{ $p->id }}" name="description-{{ $p->id }}" data-original-value="{{ $p->description }}" readonly />
                        </td>
                        <td class="editable">
                            <input class="form-control text" value="{{ $p->note }}" id="note-{{ $p->id }}" name="note-{{ $p->id }}" data-original-value="{{ $p->note }}" readonly />
                        </td>
                        <td id="status-{{ $p->id }}">
                            @if ($p->deleted_at)
                                <span class="badge badge-pill badge-danger mt-2">Disabled</span>
                            @else
                                <span class="badge badge-pill badge-success mt-2">Active</span>
                            @endif
                        </td>
                        <td class="actions">
                            <div class="edit-disable-btns mt-2">
                                <a role="button" id="edit-{{ $p->id }}" class="edit" data-toggle="tooltip" data-placement="top" title="Edit Product"><span class="material-icons md-18">edit</span></a>
                                <a role="button" id="{{ $p->deleted_at ? 'restore' : 'disable' }}-{{ $p->id }}" class="{{ $p->deleted_at ? 'restore' : 'disable' }} text-{{ $p->deleted_at ? 'success' : 'danger' }}"
                                    data-toggle="tooltip" data-placement="top" title="{{ $p->deleted_at ? 'Restore' : 'Disable' }} Product"><span class="material-icons md-18">power_settings_new</span></a>
                            </div>
                            <div class="save-cancel-btns" style="display: none;">
                                <a role="button" id="save-{{ $p->id }}" class="save" data-toggle="tooltip" data-placement="top" title="Save Product"><span class="material-icons md-18">save</span></a>
                                <a role="button" id="cancel-{{ $p->id }}" class="cancel" data-toggle="tooltip" data-placement="top" title="Cancel"><span class="material-icons md-18">cancel</span></a>
                            </div>
                        </td>
                    </tr>
                </form>
            @endforeach


            <form action="" id="form-new">
                <tr id="new_product" style="display:none;" data-store='{{ route('product.store') }}'>
                    <td class="editable">
                        <input class="form-control text required" value="" id="name" name="name" />
                    </td>
                    <td>
                        <select class="product_type_select form-control" id="product_type_select">
                            @foreach ($product_types as $type)
                                <option id="product_type_{{ $type->id }}" value="{{ $type->id }}" name="{{ $type->description }}" {{ $loop->index == 1 ? 'selected' : '' }}>
                                    {{ $type->description }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="editable">
                        <input class="form-control numeric required" value="" id="amount" name="amount" />
                    </td>
                    <td class="editable">
                        <input class="form-control currency required" value="" name="price" id="price" />
                    </td>
                    <td class="editable">
                        <input class="form-control text" value="" id="description" name="description" />
                    </td>
                    <td class="editable">
                        <input class="form-control text" value="" id="note" name="note" />
                    </td>
                    <td>
                    </td>
                    <td class="actions">
                        <div class="save-cancel-btns mt-2">
                            <a role="button" id="save-new" class="save" data-toggle="tooltip" data-placement="top" title="Save Product"><span class="material-icons md-18">save</span></a>
                            <a role="button" id="cancel-new" class="cancel" data-toggle="tooltip" data-placement="top" title="Cancel"><span class="material-icons md-18">cancel</span></a>
                        </div>
                    </td>
                </tr>
            </form>

        </tbody>
    </table>
</div>

<button class="d-block btn btn-success ml-auto" id="addProduct"><i class="fas fa-plus"></i>
    Add</button>
