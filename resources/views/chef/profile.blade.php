<div class="row">

    @forelse($reseps as $resep)
        <div class="col-md-4 mb-4">

            <div class="card shadow border-0 h-100">

                @if ($resep->gambar)
                    <img src="{{ asset('storage/' . $resep->gambar) }}" class="card-img-top"
                        style="height:220px;object-fit:cover;">
                @endif

                <div class="card-body">

                    <h5 class="fw-bold">
                        {{ $resep->judul }}
                    </h5>

                    <p class="text-muted">
                        {{ Str::limit($resep->deskripsi, 100) }}
                    </p>

                    <div class="d-flex justify-content-between">

                        <span class="badge bg-warning text-dark">
                            ⏱ {{ $resep->waktu_memasak }} Menit
                        </span>

                        <span class="badge bg-success">
                            🍽 {{ $resep->porsi }} Porsi
                        </span>

                    </div>

                </div>

            </div>

        </div>

    @empty

        <div class="col-12">
            <div class="alert alert-warning text-center">
                Chef ini belum memiliki resep.
            </div>
        </div>
    @endforelse

</div>
