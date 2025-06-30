// views/dist/js/dashboard.js
console.log("🚀 dashboard.js cargado");
const _charts = {};

document.addEventListener("DOMContentLoaded", () => {
  const est = document.getElementById("areaFilter");
  const per = document.getElementById("areaFilterPersonas");
  const adq = document.getElementById("areaFilterAdquisicion");

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
        document.getElementById("HorasEntrenado").textContent =
          p.horas_entrenadas;
      })
      .catch((err) =>
        console.error("❌ Error al obtener datos de personas:", err)
      );

    fetchDashboardData("Personas_Graficos_Anual", { id_area: areaId })
      .then((arr) => {
        console.log("💡 Personas_Graficos_Anual response:", arr);
        renderBarGrouped(
          "barChart3",
          arr.map((r) => r.Anio),
          arr.map((r) => r.PersonasTotales),
          arr.map((r) => r.PersonasEntrenadas)
        );
      })
      .catch((err) => console.error("❌ Error anual personas:", err));

    // 2) Personas por Área (barChart4)
    console.log("🔍 fetch Personas_Graficos_Por_Area para área", areaId);
    fetchDashboardData("Personas_Graficos_Por_Area", { id_area: areaId })
      .then((rows) => {
        if (!Array.isArray(rows) || !rows.length) {
          console.warn("⚠️ No hay datos de personas por área");
          return;
        }
        const r = rows[0];
        const labels = [
          "Personas Totales",
          "Personas en Entrenamiento",
          "Personas Entrenadas",
          "Horas Entrenadas (hrs)",
        ];
        const datasets = [
          {
            label: "Personas Totales",
            data: [r.PersonasTotales, null, null, null],
            backgroundColor: "#081A4A",
            yAxisID: "y",
          },
          {
            label: "Personas en Entrenamiento",
            data: [null, r.PersonasEnEntrenamiento, null, null],
            backgroundColor: "#2865DF",
            yAxisID: "y",
          },
          {
            label: "Personas Entrenadas",
            data: [null, null, r.PersonasEntrenadas, null],
            backgroundColor: "#EB6D04",
            yAxisID: "y",
          },
          {
            label: "Horas Entrenadas (hrs)",
            data: [null, null, null, r.HorasEntrenadas],
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
            tooltips: {
              callbacks: {
                label: function (tooltipItem, data) {
                  const ds = data.datasets[tooltipItem.datasetIndex];
                  const val = ds.data[tooltipItem.index];
                  if (ds.yAxisID === "y1" && val != null) {
                    const totalMinutes = Math.round(val * 60);
                    const h = Math.floor(totalMinutes / 60);
                    const m = totalMinutes % 60;
                    return ds.label + ": " + h + "h " + m + "m";
                  }
                  return ds.label + ": " + val;
                },
              },
            },
            scales: {
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
                  scaleLabel: { display: true, labelString: "Horas" },
                  ticks: {
                    beginAtZero: true,
                    callback: (v) => Math.round(v) + "h",
                  },
                },
              ],
              xAxes: [
                { ticks: { display: false }, gridLines: { display: false } },
              ],
            },
            legend: { display: true, position: "top" },
          },
        });
      })
      .catch((err) => console.error("❌ Error personas por área:", err));

    // 3) Donuts por Pilar (Personas)
    fetchDashboardData("Estandares_Graficos_Pie_Pilar", { id_area: areaId })
      .then((p) => {
        console.log(p);
        const pillars = [
          {
            id: "pieChart5",
            name: "Seguridad",
            data: [p.seguridad_en_entrenamiento, p.seguridad_entrenados],
            hours: p.seguridad_horas,
          },
          {
            id: "pieChart6",
            name: "Calidad",
            data: [p.calidad_en_entrenamiento, p.calidad_entrenados],
            hours: p.calidad_horas,
          },
          {
            id: "pieChart7",
            name: "Producción",
            data: [p.produccion_en_entrenamiento, p.produccion_entrenados],
            hours: p.produccion_horas,
          },
          {
            id: "pieChart8",
            name: "5S",
            data: [p.s5_en_entrenamiento, p.s5_entrenados],
            hours: p.s5_horas,
          },
        ];
        pillars.forEach((pl, i) => {
          renderDonut(
            pl.id,
            ["En Entrenamiento", "Entrenados"],
            pl.data,
            pl.name
          );
          const el = document.getElementById(`HorasEntrenado${i + 1}`);
          if (el) el.textContent = pl.hours ?? "0";
        });
      })
      .catch((err) =>
        console.error("❌ Error al cargar donuts personas por pilar:", err)
      );
  }

  // ── ESTÁNDARES ────────────────────────────────────────────────────────────
  if (seccion === "todas" || seccion === "estandares") {
    // Totales
    fetchDashboardData("Estandares_Graficos_Creados_Entrenados", {
      id_area: areaId,
    })
      .then((res) => {
        renderDonut(
          "donutChart",
          ["Creados", "Entrenados"],
          [res.total_estandares_creados, res.total_estandares_entrenados],
          "Estándares"
        );
      })
      .catch((err) => console.error("❌ Error totales estándares:", err));

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

    // Barras mensuales
    fetchDashboardData("Estandares_Graficos_Barras_Creados", {
      id_area: areaId,
    })
      .then((arr) =>
        renderBar(
          "barChartCreados",
          arr.map((r) => r.Mes),
          arr.map((r) => r.CantidadRegistrosCreados)
        )
      )
      .catch((err) => console.error("❌ Error creados mensuales:", err));

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

    // Barras anuales
    fetchDashboardData("Estandares_Graficos_Anual", { id_area: areaId })
      .then((arr) =>
        renderBarGrouped(
          "barChart",
          arr.map((r) => r.Año),
          arr.map((r) => r.RegistrosCreados),
          arr.map((r) => r.RegistrosEntrenados)
        )
      )
      .catch((err) => console.error("❌ Error anuales estándares:", err));
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
                backgroundColor: "#3b8bba",
              },
              {
                label: "Adquiridos",
                data: [adquiridos],
                backgroundColor: "#5fa8d3",
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              yAxes: [
                {
                  ticks: { beginAtZero: true, stepSize: 1 },
                  gridLines: { color: "rgba(0,0,0,0.05)" },
                },
              ],
            },
            legend: { position: "top" },

            // <-- aquí venimos con datalabels
            plugins: {
              datalabels: {
                anchor: "center", // posición del label
                align: "center", // alineación dentro de la barra
                color: "#FFFDD0", // color del texto
                font: {
                  weight: "bold", // negrita
                  size: 14, // tamaño en px
                },
                formatter: function (value) {
                  return value; // muestra el valor bruto
                },
              },
            },
          },
        });
      })
      .catch((err) =>
        console.error("❌ Error al cargar barras de Adquisición por Área:", err)
      );
  }

  console.groupEnd();
}

function fetchDashboardData(accion, params) {
  return fetch("/SistemaEstandaresAquaChile/ajax/ajaxDashboard.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      accion,
      planta_id: PLANTA_ID,
      id_area: params.id_area,
    }),
  }).then((r) => r.json());
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

function renderDonut(canvasId, labels, data, titleText = "") {
  const total = data.reduce((sum, v) => sum + v, 0);
  const canvas = document.getElementById(canvasId);
  if (!canvas) return;

  // Si no hay datos, mostramos un mensaje en lugar de la dona
  if (total === 0) {
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.font = "16px sans-serif";
    ctx.fillStyle = "#666";
    ctx.textAlign = "center";
    ctx.fillText("Sin datos", canvas.width / 2, canvas.height / 2);
    return;
  }

  createOrUpdateChart(canvasId, {
    type: "doughnut",
    data: {
      labels,
      datasets: [{ data, backgroundColor: ["#3b8bba", "#5fa8d3"] }],
    },
    options: {
      cutoutPercentage: 60,
      title: {
        display: !!titleText,
        text: titleText,
        fontSize: 14,
      },
      legend: {
        position: "bottom",
      },
    },
  });
}

function renderBar(canvasId, labels, data) {
  createOrUpdateChart(canvasId, {
    type: "bar",
    data: {
      labels,
      datasets: [{ label: "Cantidad", data, backgroundColor: "#3b8bba" }],
    },
    options: { scales: { y: { beginAtZero: true } } },
  });
}

function renderBarGrouped(canvasId, labels, data1, data2) {
  createOrUpdateChart(canvasId, {
    type: "bar",
    data: {
      labels,
      datasets: [
        { label: "Creados", data: data1, backgroundColor: "#3b8bba" },
        { label: "Entrenados", data: data2, backgroundColor: "#5fa8d3" },
      ],
    },
    options: {
      scales: {
        yAxes: [
          {
            ticks: { beginAtZero: true, min: 0, stepSize: 1 },
            gridLines: { color: "rgba(0,0,0,0.05)" },
          },
        ],
      },
    },
  });
}
