// views/dist/js/dashboard.js
console.log("🚀 dashboard.js cargado");
const _charts = {};

document.addEventListener("DOMContentLoaded", () => {
  const est = document.getElementById("areaFilter");
  const per = document.getElementById("areaFilterPersonas");
  if (est)
    est.addEventListener("change", () =>
      updateCharts(+est.value, "estandares")
    );
  if (per)
    per.addEventListener("change", () => updateCharts(+per.value, "personas"));
  // carga inicial de ambos
  updateCharts(0, "todas");
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
          ]
        );
        document.getElementById("PorcentajeEntrenado2").textContent =
          p.porcentaje_entrenados + " %";
        document.getElementById("HorasEntrenado").textContent =
          p.horas_entrenadas;
      })
      .catch((err) =>
        console.error("❌ Error al obtener datos de personas:", err)
      );

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

            // 1) Tooltips que formatean a Hh Mm
            tooltips: {
              callbacks: {
                label: function (tooltipItem, data) {
                  const ds = data.datasets[tooltipItem.datasetIndex];
                  const raw = ds.data[tooltipItem.index];
                  // Si es la serie de Horas (yAxisID 'y1'):
                  if (ds.yAxisID === "y1" && raw != null) {
                    const totalMin = Math.round(raw * 60);
                    const h = Math.floor(totalMin / 60);
                    const m = totalMin % 60;
                    return ds.label + ": " + h + "h " + m + "m";
                  }
                  // Para las Personas:
                  return ds.label + ": " + raw;
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
                    // 2) Tick callback para mostrar horas enteras
                    callback: function (value) {
                      return Math.round(value) + "h";
                    },
                  },
                },
              ],
              xAxes: [
                {
                  ticks: { display: false },
                  gridLines: { display: false },
                },
              ],
            },

            legend: { display: true, position: "top" },
          },
        });
      })
      .catch((err) => console.error("❌ Error personas por área:", err));
  }

  // ── ESTÁNDARES ────────────────────────────────────────────────────────────
  if (seccion === "todas" || seccion === "estandares") {
    fetchDashboardData("Estandares_Graficos_Creados_Entrenados", {
      id_area: areaId,
    })
      .then((res) => {
        renderDonut(
          "donutChart",
          ["Creados", "Entrenados"],
          [res.total_estandares_creados, res.total_estandares_entrenados]
        );
      })
      .catch((err) => console.error("❌ Error totales estándares:", err));

    fetchDashboardData("Estandares_Graficos_Pie_Pilar", { id_area: areaId })
      .then((p) => {
        renderDonut(
          "pieChart",
          ["Creados", "Entrenados"],
          [p.seguridad_creados, p.seguridad_entrenados]
        );
        renderDonut(
          "pieChart2",
          ["Creados", "Entrenados"],
          [p.calidad_creados, p.calidad_entrenados]
        );
        renderDonut(
          "pieChart3",
          ["Creados", "Entrenados"],
          [p.produccion_creados, p.produccion_entrenados]
        );
        renderDonut(
          "pieChart4",
          ["Creados", "Entrenados"],
          [p.s5_creados, p.s5_entrenados]
        );
      })
      .catch((err) => console.error("❌ Error por pilar estándares:", err));

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

    fetchDashboardData("Estandares_Graficos_Por_Area", { id_area: areaId })
      .then((arr) => {
        const labels = arr.map((r) => r.Area),
          c = arr.map((r) => r.RegistrosCreados),
          e = arr.map((r) => r.RegistrosEntrenados);
        renderBarGrouped("barChart2", labels, c, e);
      })
      .catch((err) => console.error("❌ Error estándares por área:", err));

    fetchDashboardData("Estandares_Graficos_Anual", { id_area: areaId })
      .then((arr) => {
        renderBarGrouped(
          "barChart",
          arr.map((r) => r.Año),
          arr.map((r) => r.RegistrosCreados),
          arr.map((r) => r.RegistrosEntrenados)
        );
      })
      .catch((err) => console.error("❌ Error anuales estándares:", err));
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
  // destruyo si ya existía
  if (_charts[canvasId]) {
    _charts[canvasId].destroy();
  }

  // <-- Aseguro que config.options sea siempre un objeto
  const userOptions = typeof config.options === "object" ? config.options : {};
  config.options = {
    responsive: true,
    maintainAspectRatio: false,
    devicePixelRatio: window.devicePixelRatio || 1,
    ...userOptions, // ahora esto nunca petará
  };

  const ctx = canvas.getContext("2d");
  _charts[canvasId] = new Chart(ctx, config);
}

function renderDonut(canvasId, labels, data) {
  createOrUpdateChart(canvasId, {
    type: "doughnut",
    data: {
      labels,
      datasets: [{ data, backgroundColor: ["#3b8bba", "#5fa8d3"] }],
    },
  });
}

function renderBar(canvasId, labels, data) {
  createOrUpdateChart(canvasId, {
    type: "bar",
    data: {
      labels,
      datasets: [
        {
          label: "Cantidad",
          data,
          backgroundColor: "#3b8bba",
        },
      ],
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
            ticks: {
              beginAtZero: true,
              min: 0,
              stepSize: 1,
            },
            gridLines: {
              color: "rgba(0,0,0,0.05)",
            },
          },
        ],
      },
    },
  });
}
