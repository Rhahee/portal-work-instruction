<x-layouts.app title="Kelola Pengguna">
<section class="section narrow"><div class="section-heading"><div><p class="eyebrow">ADMIN</p><h1>Pengguna</h1></div></div>
<form class="user-form" method="post" action="{{ route('manage.users.store') }}">@csrf
<input name="name" placeholder="Nama lengkap" required><input name="nik" placeholder="NIK" required>
<select name="role"><option value="it">IT</option><option value="admin">Admin</option></select>
<input name="password" type="password" placeholder="Password min. 8" required><input name="password_confirmation" type="password" placeholder="Ulangi password" required><button class="button">Tambah pengguna</button></form>
<p class="metadata">Akun hanya diperlukan untuk IT dan Admin. Pembaca umum membuka WI umum tanpa login.</p>
<div class="data-table"><table><thead><tr><th>Nama</th><th>NIK</th><th>Role</th><th></th></tr></thead><tbody>@foreach($users as $user)<tr><td>{{ $user->name }}</td><td>{{ $user->nik }}</td><td><span class="tag">{{ $user->role }}</span></td><td><a href="{{ route('manage.users.edit', $user) }}">Edit</a> @if(!$user->is(auth()->user())) · <form class="inline" method="post" action="{{ route('manage.users.destroy', $user) }}">@csrf @method('DELETE')<button class="link-button danger">Hapus</button></form>@endif</td></tr>@endforeach</tbody></table></div><div class="pagination">{{ $users->links() }}</div></section>
</x-layouts.app>
