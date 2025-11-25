<!DOCTYPE html>
<html><head><title>Cetak Agenda Surat Masuk</title>
<style>body{font-family:Arial,sans-serif;margin:20px}h1{text-align:center;font-size:18px}table{width:100%;border-collapse:collapse;margin-top:20px}th,td{border:1px solid #000;padding:8px;text-align:left;font-size:12px}th{background:#f0f0f0}.header{text-align:center;margin-bottom:20px}.print-date{text-align:right;font-size:11px;margin-bottom:10px}@media print{body{margin:0}}</style>
</head><body>
<div class="header"><h1>BUKU AGENDA SURAT MASUK</h1><p>{{ config('app.name') }}</p></div>
<div class="print-date">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
<table><thead><tr><th>No</th><th>No. Agenda</th><th>No. Surat</th><th>Tanggal Surat</th><th>Tgl Diterima</th><th>Dari</th><th>Perihal</th><th>Klasifikasi</th></tr></thead>
<tbody>@forelse($letters as $i => $l)
<tr><td>{{ $i + 1 }}</td><td>{{ $l->agenda_number ?? '-' }}</td><td>{{ $l->reference_number }}</td><td>{{ $l->letter_date?->format('d/m/Y') }}</td><td>{{ $l->received_date?->format('d/m/Y') }}</td><td>{{ $l->from }}</td><td>{{ $l->description }}</td><td>{{ $l->classification?->type }}</td></tr>
@empty<tr><td colspan="8" style="text-align:center">Tidak ada data</td></tr>@endforelse
</tbody></table>
<script>window.onload=function(){window.print()}</script>
</body></html>
