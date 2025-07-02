// views/dist/js/dashboard.js
console.log("🚀 dashboard.js cargado");

Chart.plugins.register(ChartDataLabels);

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
      datasets: [
        {
          data,
          backgroundColor: ["#3b8bba", "#5fa8d3"],
        },
      ],
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
      // aquí activamos datalabels para el donut
      plugins: {
        datalabels: {
          anchor: "center", // centra el label en el medio de cada porción
          align: "center",
          color: "#FFFDD0", // color del texto
          font: {
            weight: "bold",
            size: 14,
          },
          formatter: (value, ctx) => {
            // si quieres el número crudo:
            return value;
            // o si prefieres %:
            // const sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
            // return sum ? Math.round(value / sum * 100) + "%" : "";
          },
        },
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
    options: {
      scales: {
        y: { beginAtZero: true },
        plugins: {
          datalabels: {
            anchor: "center", // centra el label en el medio de cada porción
            align: "center",
            color: "#FFFDD0", // color del texto
            font: {
              weight: "bold",
              size: 14,
            },
            formatter: (value, ctx) => {
              // si quieres el número crudo:
              return value;
              // o si prefieres %:
              // const sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
              // return sum ? Math.round(value / sum * 100) + "%" : "";
            },
          },
        },
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
