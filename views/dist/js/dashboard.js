// views/dist/js/dashboard.js

let plantaActual = PLANTA_ID;
const esSuperadmin = USER_NIVEL === 4;
const _charts = {};
console.log("🚀 dashboard.js cargado", { plantaActual, esSuperadmin });

Chart.plugins.register(ChartDataLabels);

// ────────────────────────────────────────────────────────────
// 1) “Real” fetch al back-end
// ────────────────────────────────────────────────────────────

// Guardamos la función real para poder invocarla después
//const realFetchDashboardData = fetchDashboardData;

// ────────────────────────────────────────────────────────────
// 2) WRAPPER que elige mock o real según plantaActual
// ────────────────────────────────────────────────────────────

//LOCAL
//function fetchDashboardData(accion, params) {
//  return fetch("/SistemaEstandaresAquaChile/ajax/ajaxDashboard.php", {
//    method: "POST",
//    headers: { "Content-Type": "application/json" },
//    body: JSON.stringify({ accion, planta_id: plantaActual, ...params }),
//  }).then(r => r.json());
//}

function fetchDashboardData(accion, params) {
  return fetch("/ajax/ajaxDashboard.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ accion, planta_id: plantaActual, ...params }),
  }).then(r => r.json());
}


document.addEventListener("DOMContentLoaded", () => {
  const est = document.getElementById("areaFilter");
  const per = document.getElementById("areaFilterPersonas");
  const adq = document.getElementById("areaFilterAdquisicion");

  const selectPlanta = document.getElementById("plantaFilter");

  // 2) SI EXISTE EL SELECT DE PLANTA, escucha sus cambios:
  if (selectPlanta) {
    selectPlanta.addEventListener("change", () => {
      plantaActual = Number(selectPlanta.value);
      // refresca la pestaña activa:
      const activeId = document.querySelector("#myTab .active").id;
      const areaId =
        activeId === "home-tab"
          ? +est.value
          : activeId === "profile-tab"
          ? +per.value
          : +adq.value;
      const seccion =
        activeId === "home-tab"
          ? "estandares"
          : activeId === "profile-tab"
          ? "personas"
          : "adquisicion";
      updateCharts(areaId, seccion);
    });
  }

  // ── FILTROS DE ÁREA ─────────────────────────────────────────────────────────
  if (est) {
    est.addEventListener("change", () => {
      const activeId = document.querySelector("#myTab .active").id;
      if (activeId === "home-tab") {
        updateCharts(+est.value, "estandares");
      }
      if (activeId === "profile-tab") {
        updateCharts(+per.value, "personas");
      }
      if (activeId === "adquisicion-tab") {
        updateCharts(+adq.value, "adquisicion");
      }
    });
  }

  if (per) {
    per.addEventListener("change", () => updateCharts(+per.value, "personas"));
  }

  if (adq) {
    adq.addEventListener("change", () =>
      updateCharts(+adq.value, "adquisicion")
    );
  }

  // ── CUANDO SE MUESTRA UNA PESTAÑA ─────────────────────────────────────────────
  $('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
    if (e.target.id === "home-tab") {
      updateCharts(+est.value, "estandares");
    }
    if (e.target.id === "profile-tab") {
      updateCharts(+per.value, "personas");
    }
    if (e.target.id === "adquisicion-tab") {
      updateCharts(+adq.value, "adquisicion");
    }
  });

  // ── CARGA INICIAL ────────────────────────────────────────────────────────────
  updateCharts(0, "estandares");
});

function updateCharts(areaId, seccion = "todas") {
  console.group(`📊 updateCharts(${areaId}, sección=${seccion})`);

  // ── PERSONAS ───────────────────────────────────────────────────────────────
  if (seccion === "todas" || seccion === "personas") {
    // 1) Donut de entrenamientos
    fetchDashboardData("Personas_Graficos_Creados_Entrenados", {
      id_area: areaId,
    })
      .then((p) => {
        renderDonut(
          "donutChart2",
          ["Entrenamientos Disponibles", "Entrenamientos Ejecutados"],
          [
            p.total_entrenamientos_disponibles,
            p.total_entrenamientos_ejecutados,
          ],
          "Entrenamientos"
        );
        document.getElementById("PorcentajeEntrenado2").textContent =
          p.porcentaje_entrenados + " %";

        // =====> Nuevo cálculo en semanas/días/horas <=====
        let totalHrs = parseFloat(p.horas_entrenadas) || 0;
        const weeks = Math.floor(totalHrs / (24 * 7));
        totalHrs -= weeks * 24 * 7;
        const days = Math.floor(totalHrs / 24);
        totalHrs -= days * 24;
        const hours = Math.round(totalHrs); // redondea

        // montar líneas solo si hay valor
        const parts = [];
        if (weeks > 0) parts.push(`${weeks} semana${weeks > 1 ? "s" : ""}`);
        if (days > 0) parts.push(`${days} día${days > 1 ? "s" : ""}`);
        if (hours > 0) parts.push(`${hours} hora${hours > 1 ? "s" : ""}`);
        if (parts.length === 0) parts.push("0 horas");

        // inyectar con saltos de línea
        document.getElementById("HorasEntrenado").innerHTML = parts.join("\n");
      })
      .catch((err) =>
        console.error("❌ Error al obtener datos de personas:", err)
      );

    // ── Personas Totales vs Personas Entrenadas Anual ─────────────────────────
    fetchDashboardData("Personas_Graficos_Anual", { id_area: areaId })
      .then((arr) => {
        const labels = arr.map((r) => r.Anio);
        const totales = arr.map((r) => r.PersonasTotales);
        const entrenadas = arr.map((r) => r.PersonasEntrenadas);
        const horas = arr.map((r) => +r.HorasEntrenadas); // en horas

        createOrUpdateChart("barChart3", {
          type: "bar",
          data: {
            labels,
            datasets: [
              {
                label: "Personas en Entrenamiento",
                data: totales,
                backgroundColor: "#081A4A",
                // Opción: grosor fijo
                // barThickness: 40
              },
              {
                label: "Personas Entrenadas",
                data: entrenadas,
                backgroundColor: "#EB6D04",
                // barThickness: 40
              },
              {
                label: "Horas Entrenadas",
                data: horas,
                type: "line",
                yAxisID: "y1",
                borderColor: "#FBDA61",
                fill: false,
                pointRadius: 4,
                tension: 0,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
              position: "top",
              labels: { boxWidth: 12, fontSize: 12 },
            },
            scales: {
              xAxes: [
                {
                  gridLines: { display: false },
                  // haz que las barras llenen más el espacio:
                  categoryPercentage: 0.8, // antes 0.6
                  barPercentage: 0.8, // antes 0.5
                  // si prefieres usar grosor fijo en px, descomenta barThickness arriba
                },
              ],
              yAxes: [
                {
                  id: "y",
                  position: "left",
                  ticks: {
                    beginAtZero: true,
                    callback: (v) => v.toLocaleString(),
                  },
                  scaleLabel: { display: true, labelString: "Personas" },
                  gridLines: { color: "rgba(0,0,0,0.05)" },
                },
                {
                  id: "y1",
                  position: "right",
                  ticks: { beginAtZero: true },
                  scaleLabel: { display: true, labelString: "Horas" },
                  gridLines: { drawOnChartArea: false },
                },
              ],
            },
            plugins: {
              datalabels: {
                display: true,
                anchor: "center",
                align: "center",
                color: "#FFF",
                font: { weight: "bold", size: 12 },
                formatter: (value, ctx) => {
                  if (ctx.dataset.type === "line") {
                    return Math.round(value) + "h";
                  }
                  return value.toLocaleString();
                },
              },
            },
            tooltips: {
              mode: "index",
              intersect: false,
              callbacks: {
                label: (item, data) => {
                  const ds = data.datasets[item.datasetIndex];
                  const v = ds.data[item.index];
                  if (ds.type === "line") {
                    return `${ds.label}: ${Math.round(v)}h`;
                  }
                  return `${ds.label}: ${v.toLocaleString()}`;
                },
              },
            },
          },
        });
      })
      .catch((err) => console.error("❌ Error anual personas:", err));

    // 2) Personas por Área (barChart4)
    console.log("🔍 fetch Personas_Graficos_Por_Area para área", areaId);
    fetchDashboardData("Personas_Graficos_Por_Area", { id_area: areaId })
      .then((rows) => {
        if (!rows.length) return;
        const r = rows[0];

        // -- un solo punto X --
        const labels = ["Total Área"];
        const minutes = Math.round(r.HorasEntrenadas * 60);

        // -- 4 series, la 4ª es Horas Entrenadas --
        const datasets = [
          {
            label: "Personas Totales",
            data: [r.PersonasTotales],
            backgroundColor: "#081A4A",
            yAxisID: "y",
          },
          {
            label: "Personas en Entrenamiento",
            data: [r.PersonasEnEntrenamiento],
            backgroundColor: "#2865DF",
            yAxisID: "y",
          },
          {
            label: "Personas Entrenadas",
            data: [r.PersonasEntrenadas],
            backgroundColor: "#EB6D04",
            yAxisID: "y",
          },
          {
            label: "Horas Entrenadas",
            data: [minutes],
            backgroundColor: "#FABE34",
            yAxisID: "y1",
          },
        ];

        createOrUpdateChart("barChart4", {
          type: "bar",
          data: { labels, datasets },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              xAxes: [
                {
                  gridLines: { display: false },
                  ticks: { display: true },
                },
              ],
              yAxes: [
                {
                  id: "y",
                  position: "left",
                  ticks: { beginAtZero: true },
                  scaleLabel: { display: true, labelString: "Personas" },
                },
                {
                  id: "y1",
                  position: "right",
                  gridLines: { drawOnChartArea: false },
                  scaleLabel: {
                    display: true,
                    labelString: "Horas Entrenadas",
                  },
                  ticks: {
                    beginAtZero: true,
                    // mostramos sólo horas enteras en el eje
                    callback: (v) => {
                      const h = Math.floor(v / 60);
                      return h + "h";
                    },
                  },
                },
              ],
            },
            legend: { position: "top" },
            tooltips: {
              callbacks: {
                label: (tt, data) => {
                  const ds = data.datasets[tt.datasetIndex];
                  const v = ds.data[tt.index];
                  if (ds.yAxisID === "y1") {
                    const h = Math.floor(v / 60);
                    const m = v % 60;
                    return `Horas entrenadas: ${h}h ${m}m`;
                  }
                  return `${ds.label}: ${v}`;
                },
              },
            },
            plugins: {
              datalabels: {
                anchor: "center",
                align: "center",
                color: "#FFF",
                font: { weight: "bold", size: 14 },
                formatter: (val, ctx) => {
                  if (ctx.dataset.yAxisID === "y1") {
                    // val está en minutos
                    const h = Math.floor(val / 60);
                    const m = val % 60;
                    return `${h}h ${m}m`;
                  }
                  return val;
                },
              },
            },
          },
        });
      })
      .catch((err) => console.error("❌ Error personas por área:", err));

    // ── 3) Donuts por Pilar (Personas)
    fetchDashboardData("Personas_Graficos_Pie_Pilar", { id_area: areaId })
      .then((rows) => {
        if (!Array.isArray(rows) || rows.length === 0) {
          console.warn("⚠️ No hay datos de personas por pilar");
          return;
        }
        rows.forEach((r, i) => {
          // usamos los nuevos campos: r.PersonasCreadas, r.PersonasEntrenadas
          renderDonut(
            `pieChart${5 + i}`,
            ["Personas Creadas", "Personas Entrenadas"],
            [+r.PersonasCreadas, +r.PersonasEntrenadas],
            r.Pilar
          );
          // mostramos las horas entrenadas a partir de r.HorasEntrenadas
          const minutos = Math.round(r.HorasEntrenadas * 60);
          const h = Math.floor(minutos / 60);
          const m = minutos % 60;
          const el = document.getElementById(`HorasEntrenado${i + 1}`);
          if (el) el.textContent = `${h}h ${m}m`;
        });
      })
      .catch((err) =>
        console.error("❌ Error al cargar donuts personas por pilar:", err)
      );
  }

  // ── ESTÁNDARES ────────────────────────────────────────────────────────────
  if (seccion === "todas" || seccion === "estandares") {
    // Totales
    // ─────────────────────────────────────────────────────────────────────────────
    // Donut “Creados vs Entrenados” + % Entrenados
    fetchDashboardData("Estandares_Graficos_Creados_Entrenados", {
      id_area: areaId,
    })
      .then((res) => {
        // 1) parseamos valores
        const creados = +res.total_estandares_creados;
        const entrenados = +res.total_estandares_entrenados;

        // 2) dibujamos el donut
        renderDonut(
          "donutChart",
          ["Creados", "Entrenados"],
          [creados, entrenados],
          "Estándares"
        );

        // 3) calculamos porcentaje
        //const total = creados + entrenados;
        const porcentaje = Math.round((entrenados * 100) / creados);
        //const pct = total > 0 ? Math.round((entrenados / total) * 100) : 0;

        // 4) volcamos al DOM en tu <h3 id="PorcentajeEntrenado">
        const el = document.getElementById("PorcentajeEntrenado");
        if (el) el.textContent = porcentaje + " %";
      })
      .catch((err) => console.error("❌ Error totales estándares:", err));
    // ─────────────────────────────────────────────────────────────────────────────

    // Pie por Pilar (Estándares)
    fetchDashboardData("Estandares_Graficos_Pie_Pilar", { id_area: areaId })
      .then((p) => {
        renderDonut(
          "pieChart",
          ["Creados", "Entrenados"],
          [p.seguridad_creados, p.seguridad_entrenados],
          "Seguridad"
        );
        renderDonut(
          "pieChart2",
          ["Creados", "Entrenados"],
          [p.calidad_creados, p.calidad_entrenados],
          "Calidad"
        );
        renderDonut(
          "pieChart3",
          ["Creados", "Entrenados"],
          [p.produccion_creados, p.produccion_entrenados],
          "Producción"
        );
        renderDonut(
          "pieChart4",
          ["Creados", "Entrenados"],
          [p.s5_creados, p.s5_entrenados],
          "5S"
        );
      })
      .catch((err) => console.error("❌ Error pie estándares por pilar:", err));

    fetchDashboardData("Estandares_Graficos_Barras_Entrenados", {
      id_area: areaId,
    })
      .then((arr) =>
        renderBar(
          "barChartEntrenados",
          arr.map((r) => r.Mes),
          arr.map((r) => r.CantidadRegistrosEntrenados)
        )
      )
      .catch((err) => console.error("❌ Error entrenados mensuales:", err));

    // Barras agrupadas por Área
    fetchDashboardData("Estandares_Graficos_Por_Area", { id_area: areaId })
      .then((arr) => {
        renderBarGrouped(
          "barChart2",
          arr.map((r) => r.Area),
          arr.map((r) => r.RegistrosCreados),
          arr.map((r) => r.RegistrosEntrenados)
        );
      })
      .catch((err) => console.error("❌ Error estándares por área:", err));

    // GRAFICOS ANUAL Y MENSUAL
    const paramsBase = { planta_id: plantaActual, id_area: areaId };
    const $year = document.getElementById("yearFilter");
    const monthNames = [
      "Ene",
      "Feb",
      "Mar",
      "Abr",
      "May",
      "Jun",
      "Jul",
      "Ago",
      "Sep",
      "Oct",
      "Nov",
      "Dic",
    ];

    // ——————————————————————————
    // 1) Carga ANUAL y llena el <select>
    // ——————————————————————————
    // 1) Carga ANUAL y llena el <select>
    // ——————————————————————————
    fetchDashboardData("Estandares_Graficos_Anual", paramsBase)
      .then((arr) => {
        // 1a) Renderizar gráfico ANUAL
        renderBarGrouped(
          "barChart",
          arr.map((r) => r.Año),
          arr.map((r) => r.RegistrosCreados),
          arr.map((r) => r.RegistrosEntrenados)
        );

        // 1b) Poblar el select de años
        $year.innerHTML = ""; // limpiar opciones previas
        arr.forEach((r) => {
          const opt = document.createElement("option");
          opt.value = r.Año;
          opt.text = r.Año;
          $year.add(opt);
        });

        // 1c) Seleccionar primer año y disparar carga mensual
        if (arr.length > 0) {
          $year.value = arr[0].Año;
          $year.dispatchEvent(new Event("change"));
        }
      })
      .catch((err) =>
        console.error("❌ Error al cargar datos anuales de estándares:", err)
      );

    // ——————————————————————————
    // 2) Al cambiar año → gráfico MENSUAL
    // ——————————————————————————
    // 2) Al cambiar año → gráfico MENSUAL con nombres de meses
    // ——————————————————————————
    $year.addEventListener("change", () => {
      const selectedYear = parseInt($year.value, 10);

      fetchDashboardData("Estandares_Graficos_Anual", {
        ...paramsBase,
        anio: selectedYear,
      })
        .then((arr) => {
          // Solo usamos el nombre del mes, sin año
          const labels = arr.map((r) => monthNames[r.Mes - 1]);

          renderBarGrouped(
            "barChart",
            labels,
            arr.map((r) => r.RegistrosCreados),
            arr.map((r) => r.RegistrosEntrenados)
          );
        })
        .catch((err) =>
          console.error(
            "❌ Error al cargar datos mensuales de estándares:",
            err
          )
        );
    });

    // HASTA AQUI MODIFICA
  }

  if (seccion == "todas" || seccion === "adquisicion") {
    fetchDashboardData("Estandares_Graficos_Entrenados_Adquiridos", {
      id_area: areaId,
    })
      .then((p) => {
        // 1) Coerce a números
        const entrenados = +p.total_estandares_entrenados;
        const adquiridos = +p.total_estandares_adquiridos;

        // 2) Render del donut
        renderDonut(
          "donutChartAdquisicion",
          ["Entrenados", "Adquiridos"],
          [entrenados, adquiridos],
          "Entrenados vs Adquiridos"
        );

        // 3) % sobre los adquiridos
        const total = entrenados + adquiridos;
        const pct = total > 0 ? Math.round((entrenados / total) * 100) : 0;

        document.getElementById("PorcentajeEntrenadoAdquisicion").textContent =
          pct + " %";
      })
      .catch((err) =>
        console.error("❌ Error al cargar datos de Adquisición:", err)
      );

    // 2) Barras de Entrenados vs Adquiridos
    fetchDashboardData("Estandares_Graficos_Por_Area_Adquisicion", {
      id_area: areaId,
    })
      .then((p) => {
        const entrenados = +p.total_entrenados || 0;
        const adquiridos = +p.total_adquiridos || 0;

        createOrUpdateChart("barChartAdquisicion", {
          type: "bar",
          data: {
            labels: ["Total Área"],
            datasets: [
              {
                label: "Entrenados",
                data: [entrenados],
                backgroundColor: "#EB6D04", // naranja
              },
              {
                label: "Adquiridos",
                data: [adquiridos],
                backgroundColor: "#081A4A", // azul oscuro
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              yAxes: [
                {
                  ticks: { beginAtZero: true, stepSize: 100 },
                  gridLines: { color: "rgba(0,0,0,0.05)" },
                },
              ],
            },
            legend: { position: "top" },
            plugins: {
              datalabels: {
                anchor: "center",
                align: "center",
                color: "#FFFDD0",
                font: { weight: "bold", size: 14 },
                formatter: (v) => v,
              },
            },
          },
        });
      })
      .catch((err) =>
        console.error("❌ Error al cargar barras de Adquisición por Área:", err)
      );

    // ── 3) Entrenados vs Estándares Adquiridos Anual ───────────────────────────
    fetchDashboardData("Estandares_Graficos_Adquisicion_Anual", {
      id_area: areaId,
    })
      .then((arr) => {
        const mesesEs = [
          "Enero",
          "Febrero",
          "Marzo",
          "Abril",
          "Mayo",
          "Junio",
          "Julio",
          "Agosto",
          "Septiembre",
          "Octubre",
          "Noviembre",
          "Diciembre",
        ];
        // Etiquetas
        const labels = arr.map((r) => mesesEs[r.MesNum - 1] || "");

        // Valores por pilar
        const dsSeg = arr.map((r) => +r.Seguridad);
        const dsCal = arr.map((r) => +r.Calidad);
        const dsProd = arr.map((r) => +r.Producción);
        const ds5S = arr.map((r) => +r["5S"]);

        // Totales y % para el footer
        const dsAdq = arr.map((r) => +r.TotalAdquiridos);
        const dsPct = arr.map((r) => +r.PorcentajeCumplimiento);

        // Objetivo al 80%
        const dsObj = labels.map(() => 80);

        createOrUpdateChart("barChartAdquisicionAnual", {
          type: "bar",
          data: {
            labels,
            datasets: [
              // El orden importa: primero las 4 barras
              {
                label: "Seguridad",
                data: dsSeg,
                backgroundColor: "#081A4A",
                stack: "pilar",
              },
              {
                label: "Calidad",
                data: dsCal,
                backgroundColor: "#EB6D04",
                stack: "pilar",
              },
              {
                label: "Producción",
                data: dsProd,
                backgroundColor: "#C0C0C0",
                stack: "pilar",
              },
              {
                label: "5S",
                data: ds5S,
                backgroundColor: "#707070",
                stack: "pilar",
              },
              // Luego la línea discontinua (objetivo)
              {
                label: "Objetivo",
                type: "line",
                data: dsObj,
                yAxisID: "y1",
                borderDash: [5, 5],
                borderColor: "#181818",
                fill: false,
                pointRadius: 0,
              },
              // Finalmente la línea sólida de % Cumplimiento
              {
                label: "% Cumplimiento",
                type: "line",
                data: dsPct,
                yAxisID: "y1",
                borderColor: "#999",
                fill: false,
                pointRadius: 4,
                tension: 0,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
              position: "top",
              labels: {
                usePointStyle: true, // hace que la leyenda use los “puntitos/líneas”
                boxWidth: 12,
              },
            },
            scales: {
              xAxes: [
                {
                  stacked: true,
                  gridLines: { color: "rgba(0,0,0,0.05)" },
                },
              ],
              yAxes: [
                {
                  id: "y",
                  position: "left",
                  stacked: true,
                  scaleLabel: {
                    display: true,
                    labelString: "Cantidad de Estándares",
                  },
                  ticks: { beginAtZero: true },
                },
                {
                  id: "y1",
                  position: "right",
                  stacked: false,
                  scaleLabel: {
                    display: true,
                    labelString: "% Cumplimiento",
                  },
                  ticks: {
                    beginAtZero: true,
                    max: 100,
                    callback: (v) => v + "%",
                  },
                  gridLines: { drawOnChartArea: false },
                },
              ],
            },
            plugins: {
              datalabels: {
                // sólo para los segmentos de barra
                display: (ctx) => ctx.dataset.type === "bar",
                color: "#FFF",
                font: { weight: "bold", size: 12 },
                anchor: "center",
                align: "center",
                formatter: (v) => v || "",
              },
            },
            tooltips: {
              mode: "index",
              intersect: false,
              callbacks: {
                title: (items) => items[0].label,
                label: (item, data) => {
                  const ds = data.datasets[item.datasetIndex];
                  if (item.datasetIndex < 4) {
                    return `${ds.label}: ${ds.data[item.index]} estándares`;
                  }
                  if (ds.label === "% Cumplimiento") {
                    return `${ds.label}: ${ds.data[item.index]}%`;
                  }
                  return null;
                },
                afterBody: (items) => {
                  const i = items[0].index;
                  const totalStd = dsSeg[i] + dsCal[i] + dsProd[i] + ds5S[i];
                  return [
                    "────────────",
                    `Total Estándares: ${totalStd}`,
                    `Adquiridos: ${arr[i].TotalAdquiridos}`,
                    `Cumplimiento: ${dsPct[i]}%`,
                  ];
                },
              },
            },
          },
        });
      })
      .catch((err) => console.error("❌ Error anual Adquisición:", err));

    // ── 4) Donuts por Pilar (Adquisición) ───────────────────────────────────────
    // dentro de updateCharts(), al final de la sección if (seccion==='adquisicion') { …
    fetchDashboardData("Estandares_Graficos_Pie_Pilar_Adquisicion", {
      id_area: areaId,
    })
      .then((rows) => {
        console.log("🔥 Pie Pilar Adquisición response:", rows);
        if (!Array.isArray(rows) || rows.length === 0) {
          console.warn("⚠️ No hay datos de adquisición por pilar");
          return;
        }
        rows.forEach((p, i) => {
          renderDonut(
            `pieChartAdquisicion${i + 1}`,
            ["Entrenados", "Adquiridos"],
            [+p.entrenados, +p.adquiridos],
            p.Pilar
          );
        });
      })
      .catch((err) =>
        console.error("❌ Error al cargar donuts adquisición por pilar:", err)
      );
  }

  console.groupEnd();
}



function createOrUpdateChart(canvasId, config) {
  console.log("⚙️ createOrUpdateChart llamado con config:", config);
  const canvas = document.getElementById(canvasId);
  if (!canvas) {
    console.warn(`⚠️ Canvas ${canvasId} no existe, salto render.`);
    return;
  }
  if (_charts[canvasId]) _charts[canvasId].destroy();
  const userOptions = typeof config.options === "object" ? config.options : {};
  config.options = {
    responsive: true,
    maintainAspectRatio: false,
    devicePixelRatio: window.devicePixelRatio || 1,
    ...userOptions,
  };
  const ctx = canvas.getContext("2d");
  _charts[canvasId] = new Chart(ctx, config);
}

function renderDonut(
  canvasId,
  labels,
  data,
  titleText = "",
  outsideLabels = false
) {
  const total = data.reduce((sum, v) => sum + v, 0);
  const canvas = document.getElementById(canvasId);
  if (!canvas) return;

  if (total === 0) {
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.font = "16px sans-serif";
    ctx.fillStyle = "#666";
    ctx.textAlign = "center";
    ctx.fillText("Sin datos", canvas.width / 2, canvas.height / 2);
    return;
  }

  // ← aquí extendemos el colorMap
  const colorMap = {
    // Para Estandares
    Creados: "#081A4A",
    Entrenados: "#EB6D04",
    Adquiridos: "#081A4A",
    // Para Personas
    "Entrenamientos Disponibles": "#081A4A", // azul oscuro
    "Entrenamientos Ejecutados": "#EB6D04", // naranja
    //personas x pilar
    "Personas Creadas": "#081A4A",
    "Personas Entrenadas": "#EB6D04",
  };

  const bgColors = labels.map((l) => colorMap[l] || "#ccc");

  const datalabelsConfig = {
    formatter: (v) => v,
    backgroundColor: "#fff",
    borderWidth: 2,
    borderColor: (ctx) => bgColors[ctx.dataIndex],
    borderRadius: 4,
    padding: 6,
    anchor: outsideLabels ? "end" : "center",
    align: outsideLabels ? "start" : "center",
    offset: outsideLabels ? 8 : 0,
    color: (ctx) => bgColors[ctx.dataIndex],
  };

  createOrUpdateChart(canvasId, {
    type: "doughnut",
    data: { labels, datasets: [{ data, backgroundColor: bgColors }] },
    options: {
      cutoutPercentage: 60,
      title: { display: !!titleText, text: titleText, fontSize: 14 },
      legend: { position: "bottom" },
      plugins: { datalabels: datalabelsConfig },
    },
  });
}

function renderBar(canvasId, labels, data, barColor = "#EB6D04") {
  const canvas = document.getElementById(canvasId);
  if (!canvas) return;

  // Destruye si ya existía
  if (_charts[canvasId]) {
    _charts[canvasId].destroy();
  }

  // Si me pasan un solo color, lo replico por cada barra
  const bgColors = Array.isArray(barColor)
    ? barColor
    : labels.map(() => barColor);

  // Crea el chart
  const ctx = canvas.getContext("2d");
  _charts[canvasId] = new Chart(ctx, {
    type: "bar",
    data: {
      labels,
      datasets: [
        {
          label: "Cantidad",
          data,
          backgroundColor: bgColors,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
        },
      },
      plugins: {
        // Aquí debe ir DataLabels en v3
        datalabels: {
          anchor: "center",
          align: "center",
          color: "#FFFDD0",
          font: { weight: "bold", size: 14 },
          formatter: (value) => value,
        },
        // …y puedes añadir otros plugins aquí
      },
    },
  });
}

function renderBarGrouped(canvasId, labels, data1, data2) {
  createOrUpdateChart(canvasId, {
    type: "bar",
    data: {
      labels,
      datasets: [
        {
          label: "Creados",
          data: data1,
          backgroundColor: "#081A4A", // ← azul oscuro
          barPercentage: 0.9,
          categoryPercentage: 0.9,
        },
        {
          label: "Entrenados",
          data: data2,
          backgroundColor: "#EB6D04", // ← naranja
          barPercentage: 0.9,
          categoryPercentage: 0.9,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        xAxes: [
          {
            display: true,
            offset: true,
            gridLines: { display: false },
            ticks: {
              autoSkip: false,
              maxRotation: 0,
              minRotation: 0,
              maxTicksLimit: labels.length,
              fontSize: 12,
            },
          },
        ],
        yAxes: [
          {
            ticks: { beginAtZero: true, stepSize: 40 },
            gridLines: { color: "rgba(0,0,0,0.05)" },
          },
        ],
      },
      plugins: {
        datalabels: {
          anchor: "center",
          align: "center",
          color: "#FFF",
          font: { weight: "bold", size: 14 },
          formatter: (v) => v,
        },
      },
    },
  });
}
