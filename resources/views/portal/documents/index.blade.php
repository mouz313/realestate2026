@extends('portal.layouts.app')

@section('title', 'My Documents')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="ti ti-files me-1"></i> My Documents <span class="urdu">(میری دستاویزات)</span></h4>
    <a href="{{ route('portal.documents.create') }}" class="btn btn-dark btn-sm">
        <i class="ti ti-upload"></i> Upload Document <span class="urdu">(دستاویز اپ لوڈ)</span>
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Type <span class="urdu">(قسم)</span></th>
                        <th>File <span class="urdu">(فائل)</span></th>
                        <th>Notes <span class="urdu">(نوٹس)</span></th>
                        <th>Uploaded <span class="urdu">(اپ لوڈ)</span></th>
                        <th class="text-end">Actions <span class="urdu">(کارروائیاں)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td class="fw-semibold">{{ ucfirst($doc->document_type) }}</td>
                        <td>
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-decoration-none">
                                <i class="ti ti-file-download"></i> View <span class="urdu">(دیکھیں)</span>
                            </a>
                        </td>
                        <td class="text-secondary">{{ $doc->notes ?? '-' }}</td>
                        <td class="text-secondary">{{ $doc->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="action-btns justify-content-end">
                                <form action="{{ route('portal.documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Delete this document?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="text-center text-secondary py-5">
                                <i class="ti ti-files" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                                <p>No documents uploaded yet. <span class="urdu">(کوئی دستاویز اپ لوڈ نہیں)</span></p>
                                <a href="{{ route('portal.documents.create') }}" class="btn btn-dark btn-sm">Upload your first document <span class="urdu">(اپنی پہلی دستاویز اپ لوڈ کریں)</span></a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
