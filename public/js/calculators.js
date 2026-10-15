(function () {
  const pln = (n) =>
    new Intl.NumberFormat("pl-PL", {
      style: "currency",
      currency: "PLN",
      maximumFractionDigits: 0,
    }).format(n);

  const num = (n, digits = 1) =>
    new Intl.NumberFormat("pl-PL", {
      maximumFractionDigits: digits,
      minimumFractionDigits: digits,
    }).format(n);

  function showResult(root, fields) {
    root.hidden = false;
    Object.entries(fields).forEach(([key, value]) => {
      const el = root.querySelector(`[data-field="${key}"]`);
      if (el) el.textContent = value;
    });
  }

  const insulation = document.getElementById("calc-insulation");
  if (insulation) {
    insulation.addEventListener("submit", (e) => {
      e.preventDefault();
      const fd = new FormData(insulation);
      const area = parseFloat(String(fd.get("area")));
      const thicknessCm = parseFloat(String(fd.get("thickness")));
      const material = String(fd.get("material"));

      const prices = {
        eps: 280,
        eps_graphite: 360,
        wool: 420,
      };
      const labels = {
        eps: "styropian EPS",
        eps_graphite: "styropian grafitowy",
        wool: "wełna mineralna",
      };

      const thicknessM = thicknessCm / 100;
      const volume = area * thicknessM;
      const pricePerM3 = prices[material] || prices.eps;
      const cost = volume * pricePerM3;

      showResult(document.getElementById("calc-insulation-result"), {
        summary: `Na ${num(area, 0)} m² ścian (~${thicknessCm} cm ${labels[material]})`,
        volume: `${num(volume)} m³ płyt`,
        cost: `${pln(cost)} (orientacyjnie)`,
        note: "Bez kleju, siatki, kołków i robocizny. Ceny rynkowe zmieniają się — traktuj to jako punkt startowy.",
      });
    });
  }

  const concrete = document.getElementById("calc-concrete");
  if (concrete) {
    concrete.addEventListener("submit", (e) => {
      e.preventDefault();
      const fd = new FormData(concrete);
      const length = parseFloat(String(fd.get("length")));
      const width = parseFloat(String(fd.get("width")));
      const thicknessCm = parseFloat(String(fd.get("thickness")));
      const waste = parseFloat(String(fd.get("waste")));

      const net = length * width * (thicknessCm / 100);
      const gross = net * (1 + waste / 100);
      const pricePerM3 = 380;
      const cost = gross * pricePerM3;

      showResult(document.getElementById("calc-concrete-result"), {
        summary: `Wylewka ${num(length)} × ${num(width)} m, grubość ${thicknessCm} cm`,
        net: `${num(net, 2)} m³`,
        gross: `${num(gross, 2)} m³ (zapas ${waste}%)`,
        cost: `${pln(cost)} przy ~${pln(pricePerM3)}/m³ (orientacyjnie)`,
      });
    });
  }

  const pv = document.getElementById("calc-pv");
  if (pv) {
    pv.addEventListener("submit", (e) => {
      e.preventDefault();
      const fd = new FormData(pv);
      const kwh = parseFloat(String(fd.get("kwh")));
      const coverage = parseFloat(String(fd.get("coverage")));
      const yieldPerKwp = parseFloat(String(fd.get("yield")));

      const targetKwh = kwh * (coverage / 100);
      const kwp = targetKwh / yieldPerKwp;
      const production = kwp * yieldPerKwp;
      const costLow = kwp * 4200;
      const costHigh = kwp * 6200;

      showResult(document.getElementById("calc-pv-result"), {
        summary: `Przy zużyciu ${num(kwh, 0)} kWh/rok i pokryciu ${coverage}%`,
        kwp: `${num(kwp)} kWp`,
        production: `${num(production, 0)} kWh/rok`,
        cost: `${pln(costLow)} – ${pln(costHigh)} (montaż + sprzęt, orientacyjnie)`,
      });
    });
  }
})();
