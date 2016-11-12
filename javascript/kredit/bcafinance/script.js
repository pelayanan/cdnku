var b_div = "div", b_span = "span", b_label = "label", b_input = "input", b_br = "<br/>";
function wr(wa) {
    document.write(wa);
}
function insi(wa, ind, cl, fk) {
    if (fk) {
        return '<' + wa + ' ' + cl + ' />';
    } else {
        return '<' + wa + ' ' + cl + '>' + ind + '</' + wa + '>';
    }
}
function commafy(num) {
    var str = num.toString().split('.');
    if (str[0].length >= 5) {
        str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1,');
    }
    if (str[1] && str[1].length >= 5) {
        str[1] = str[1].replace(/(\d{3})/g, '$1 ');
    }
    return str.join('.');
}
wr(
        "<form method=POST>"
        + "<div><label>Harga (OTR) Rp.<label>"
        + "<input type=number size=12 name=otr "
        + "onkeyup='Math.round(this.form.jumlahpersen.value=((this.value/100)*this.form.persen.value)); ngte(this.form);' /></div>"
        + "<br />"
        + "<div><label>Uang Muka (DP) Rp.<label>"
        + "<input type=text max=99 "
        + "onkeyup='Math.round(this.form.jumlahpersen.value=((this.form.otr.value/100)*this.value));  ngte(this.form);' name=persen value=30 size=2 /> % "
        + "<input type=text size=12 name=jumlahpersen disabled /></div>"
        + "<br />"
        + "<div><label>Jangka Waktu (tahun)</label><select name='jangka' onchange='ngte(this.form);'>"
        + "<option>1</option><option>2</option>"
        + "<option>3</option><option>4</option><option>5</option></select></div>"
        + "</form><br /><br />"
// end of form
        + "<div id=kredit_tablehasil style='display:none;'>"
        + "<table border=1>"
        + "<tr><td>Harga (OTR) Rp.</td><td id=k_hargaotr></td></tr>"
        + "<tr><td>Uang Muka (DP) Rp.</td><td id=k_uangmuka></td></tr>"
        + "<tr><td>Pokok Hutang Rp.</td><td id=k_uangpokok></td></tr>"
        + "<tr><td>Angsuran per bulan Rp.</td><td id=k_uangperbulan></td></tr>"
        + "<tr><td>Jangka Waktu (bulan)</td><td id=k_uangjangka></td></tr>"
        + "<tr><td></td><td></td></tr>"
        + "<tr><td><strong>Estimasi Total Pembayaran Pertama</strong></td><td id=k_total></td></tr>"
        + "</table>"
        + "</div>"
        );
function byIDwr(byid, wer) {
    document.getElementById(byid).innerHTML = wer;
}
function ngte(fo) {
    hargaotr = fo.otr.value;
    persen = fo.persen.value;
    jumlahuangmuka = (hargaotr / 100) * persen;
    k_uangpokok = hargaotr - jumlahuangmuka;
    jangka = fo.jangka.value;
    asuransi_persen = 5;
    angsuran_per_bulan = 0.45;
    document.getElementById("kredit_tablehasil").style.display = "block";
    // angsuran perbulan
    totalbulan = jangka * 12;
    jumlah_angsuran_perbulan = (k_uangpokok / 100) * angsuran_per_bulan;
    pembayaran_perbulan = k_uangpokok / totalbulan;
    total_bayar_perbulan = Math.round(pembayaran_perbulan + jumlah_angsuran_perbulan);
    
    byIDwr("k_hargaotr", commafy(hargaotr));
    byIDwr("k_uangmuka", commafy(jumlahuangmuka));
    byIDwr("k_uangpokok", commafy(k_uangpokok));
    byIDwr("k_uangperbulan", commafy(total_bayar_perbulan));
    byIDwr("k_uangjangka", commafy(totalbulan));
    
    jumlah_asuransi = (hargaotr / 100) * asuransi_persen;
    jumlah_provisi = (k_uangpokok / 100) * 0.5;
    polis_asuransi = 50000;
    jumlah_administrasi = 700000;
    total_pembayaran_pertama = jumlahuangmuka + total_bayar_perbulan + jumlah_asuransi + jumlah_provisi + polis_asuransi + jumlah_administrasi;
    
    byIDwr("k_total", commafy(total_pembayaran_pertama));
}