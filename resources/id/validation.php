<?php

return [
'accepted'    => ':attribute harus diterima.',
 'active_url' => ' :attribute bukan URL yang valid.',
 'after'      => 'Tanggal :attribute harus sesudah :date.',
 'alpha'      => ':attribute hanya terdiri dari huruf.',
 'alpha_dash' => ':attribute hanya boleh terdiri dari huruf, angka, dan tanda sambung.',
 'alpha_num'  => ':attribute hanya boleh terdiri dari huruf dan angka.',
 'array'      => ':attribute harus berupa larik.',
 'before'     => 'Tanggal :attribute harus sebelum :date.',
 'between'    => [
       'numeric' => ':attribute harus berkisar diantara :min sampai :max.',
       'file'    => ':attribute harus berkisar diantara :min sampai :max kilobyte.',
       'string'  => ':attribute harus terdiri dari :min sampai :max karakter.',
       'array'   => ':attribute harus terdiri dari :min sampai :max unit.',
 ],
'confirmed'       => 'Konfirmasi :attribute tidak cocok.',
 'date'           => ':attribute bukan tanggal yang valid.',
 'date_format'    => ':attribute tidak cocok dengan format :format.',
 'different'      => ':attribute dan :other harus berbeda.',
 'digits'         => ':attribute harus terdiri dari :digits digit.',
 'digits_between' => ':attribute harus berjumlah diantara :min sampai :max digit.',
 'email'          => 'Format :attribute tidak valid.',
 'exists'         => 'Yang dipilih :attribute tidak valid.',
 'image'          => ':attribute harus berupa gambar.',
 'in'             => 'Yang dipilih :attribute tidak valid.',
 'integer'        => ':attribute harus berupa bilangan bulat.',
 'ip'             => ':attribute harus berupa alamat IP yang valid.',
 'max'            => [
       'numeric' => ':attribute tidak boleh lebih besar dari :max.',
       'file'    => ':attribute tidak boleh lebih dari :max kilobyte.',
       'string'  => ':attribute tidak boleh lebih dari :max karakter.',
       'array'   => ':attribute tidak boleh memiliki lebih dari :max unit.',
 ],
'mimes' => ':attribute harus berupa berkas dengan tipe :values.',
 'min'  => [
       'numeric' => ':attribute harus berjumlah paling sedikit :min.',
       'file'    => ':attribute minimal harus sebesar :min kilobyte.',
       'string'  => ':attribute minimal harus berjumlah :min karakter.',
       'array'   => ':attribute minimal harus memiliki :min unit.',
 ],
'not_in'            => 'Yang dipilih :attribute tidak valid.',
 'numeric'          => ':attribute harus berupa angka.',
 'regex'            => 'Format :attribute tidak valid.',
 'required'         => 'Baris :attribute diperlukan.',
 'required_if'      => 'Baris :attribute diperlukan ketika :other adalah :value.',
 'required_with'    => 'Baris :attribute diperlukan ketika :values ada.',
 'required_without' => 'Baris :attribute diperlukan ketika :values tidak ada.',
 'same'             => ':attribute dan :other harus cocok.',
 'size'             => [
       'numeric' => ':attribute harus berukuran :size. ',
       'file'    => ':attribute harus berukuran :size kilobyte.',
       'string'  => ':attribute harus berjumlah :size karakter.',
       'array'   => ':attribute harus terdiri dari :size unit.',
 ],
'unique'     => ':attribute sudah dipakai.',
 'url'       => 'Format :attribute tidak valid.',
 'recaptcha' => 'Baris :attribute tidak tepat',
 ];
