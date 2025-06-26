// views/dist/js/dashboard.js
console.log("🚀 dashboard.js cargado");
const _charts = {};

document.addEventListener("DOMContentLoaded", function () {
  console.log("✅ DOM listo para inicializar dashboard");

  // Ajusta el ID según tu <select> de áreas en el HTML
  const areaSelect = document.getElementById("areaFilter");
  if (areaSelect) {
    console.log("🎯 areaSelect encontrado:", areaSelect);
    areaSelect.addEventListener("change", () => {
      const areaId = parseInt(areaSelect.value, 10) || 0;
      console.log("🔄 Área seleccionada:", areaId);
      updateCharts(areaId);
    });
  } else {
    console.warn(
      "⚠️ areaFilter no encontrado en el DOM, usando área 0 (todas)"
    );
  }

  // Carga inicial de gráficos para área = 0
  const initialArea = areaSelect ? parseInt(areaSelect.value, 10) || 0 : 0;
  updateCharts(initialArea);
});

function updateCharts(areaId) {
  console.group(`📊 updateCharts(${areaId})`);

  //PERSONAS
  fetchDashboardData("Personas_Graficos_Creados_Entrenados", {
    id_area: areaId,
  })
    .then((p) => {
      console.log("📊 Personas disponibles/ejecutadas:", p);
      // 1) Donut de entrenamientos
      renderDonut(
        "donutChart2",
        ["Entrenamientos Disponibles", "Entrenamientos Ejecutados"],
        [p.total_entrenamientos_disponibles, p.total_entrenamientos_ejecutados]
      );
      // 2) Recuadros de porcentaje y horas
      document.getElementById("PorcentajeEntrenado2").textContent =
        p.porcentaje_entrenados + " %";
      document.getElementById("HorasEntrenado").textContent =
        p.horas_entrenadas;
    })
    .catch((err) =>
      console.error("❌ Error al obtener datos de personas:", err)
    );
  // 2) Personas por Área: totales, en entrenamiento, entrenadas y horas
  // Dentro de updateCharts(), en lugar de la llamada anterior a createOrUpdateChart:
  // 2) Personas por Área

  console.log("🔍 fetch Personas_Graficos_Por_Area para área", areaId);
  fetchDashboardData("Personas_Graficos_Por_Area", { id_area: areaId })
    .then((arr) => {
      console.log("📊 Personas por área:", arr);
      if (!arr || !arr.length) {
        console.warn("⚠️ No hay datos de personas por área");
        return;
      }
      const labels = arr.map((r) => r.Area);
      const totales = arr.map((r) => r.PersonasTotales);
      const enEntre = arr.map((r) => r.PersonasEnEntrenamiento);
      const entrenadas = arr.map((r) => r.PersonasEntrenadas);
      const horas = arr.map((r) => r.HorasEntrenadas);

      // Creamos el gráfico de barras con dos ejes Y (personas/horas)
      createOrUpdateChart("barChart4", {
        type: "bar",
        data: {
          labels,
          datasets: [
            {
              label: "Personas Totales",
              data: totales,
              backgroundColor: "#081A4A",
              yAxisID: "y",
            },
            {
              label: "En Entrenamiento",
              data: enEntre,
              backgroundColor: "#2865DF",
              yAxisID: "y",
            },
            {
              label: "Entrenadas",
              data: entrenadas,
              backgroundColor: "#EB6D04",
              yAxisID: "y",
            },
            {
              label: "Horas Entrenadas (hrs)",
              data: horas,
              backgroundColor: "#FABE34",
              yAxisID: "y1",
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              position: "left",
              title: { display: true, text: "Personas" },
            },
            y1: {
              beginAtZero: true,
              position: "right",
              grid: { drawOnChartArea: false },
              title: { display: true, text: "Horas" },
            },
          },
        },
      });
    })
    .catch((err) => console.error("❌ Error personas por área:", err));

  //PERSONAS

  //ESTANDARES
  //ESTANDARES
  // 1) Donut: totales creados vs entrenados
  fetchDashboardData("Estandares_Graficos_Creados_Entrenados", {
    id_area: areaId,
  })
    .then((res) => {
      console.log("📊 Totales creados/entrenados:", res);
      renderDonut(
        "donutChart",
        ["Creados", "Entrenados"],
        [res.total_estandares_creados, res.total_estandares_entrenados]
      );
    })
    .catch((err) =>
      console.error("❌ Error al obtener totales creados/entrenados:", err)
    );

  // dentro de updateCharts()
  fetchDashboardData("Estandares_Graficos_Pie_Pilar", { id_area: areaId })
    .then((p) => {
      console.log("📊 Datos por pilar:", p);

      // Seguridad
      renderDonut(
        "pieChart",
        ["Creados", "Entrenados"],
        [p.seguridad_creados, p.seguridad_entrenados]
      );
      // Calidad
      renderDonut(
        "pieChart2",
        ["Creados", "Entrenados"],
        [p.calidad_creados, p.calidad_entrenados]
      );
      // Producción
      renderDonut(
        "pieChart3",
        ["Creados", "Entrenados"],
        [p.produccion_creados, p.produccion_entrenados]
      );
      // 5S
      renderDonut(
        "pieChart4",
        ["Creados", "Entrenados"],
        [p.s5_creados, p.s5_entrenados]
      );
    })
    .catch((err) => console.error("❌ Error al obtener datos por pilar:", err));

  // 3) Barras mensuales: creados y entrenados en los últimos 12 meses
  fetchDashboardData("Estandares_Graficos_Barras_Creados", { id_area: areaId })
    .then((arr) => {
      console.log("📈 Mensual creados:", arr);
      renderBar(
        "barChartCreados",
        arr.map((r) => r.Mes),
        arr.map((r) => r.CantidadRegistrosCreados)
      );
    })
    .catch((err) =>
      console.error("❌ Error al obtener creados mensuales:", err)
    );

  fetchDashboardData("Estandares_Graficos_Barras_Entrenados", {
    id_area: areaId,
  })
    .then((arr) => {
      console.log("📈 Mensual entrenados:", arr);
      renderBar(
        "barChartEntrenados",
        arr.map((r) => r.Mes),
        arr.map((r) => r.CantidadRegistrosEntrenados)
      );
    })
    .catch((err) =>
      console.error("❌ Error al obtener entrenados mensuales:", err)
    );

  fetchDashboardData("Estandares_Graficos_Por_Area", { id_area: areaId })
    .then((arr) => {
      console.log("📊 Por Área:", arr);
      const labels = arr.map((r) => r.Area);
      const dataC = arr.map((r) => r.RegistrosCreados);
      const dataE = arr.map((r) => r.RegistrosEntrenados);
      renderBarGrouped("barChart2", labels, dataC, dataE);
    })
    .catch((err) =>
      console.error("❌ Error al obtener creados/entrenados por área:", err)
    );

  fetchDashboardData("Estandares_Graficos_Anual", { id_area: areaId })
    .then((arr) => {
      console.log("📊 Anual creado/entrenado:", arr);
      // arr = [ { Año:2023, RegistrosCreados:10, RegistrosEntrenados:8 }, … ]
      renderBarGrouped(
        "barChart",
        arr.map((r) => r.Año),
        arr.map((r) => r.RegistrosCreados),
        arr.map((r) => r.RegistrosEntrenados)
      );
    })
    .catch((err) => console.error("❌ Error al obtener datos anuales:", err));

  console.groupEnd(); // UNA sola vez, al final
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
  // Destruye si ya existe
  if (_charts[canvasId]) {
    _charts[canvasId].destroy();
  }

  // Asegúrate de tener la sección options
  config.options = {
    // Hace al chart responsive al tamaño del contenedor
    responsive: true,
    // Ignora la proporción original y estira al 100% del contenedor
    maintainAspectRatio: false,
    // Para pantallas retina / Hi-DPI
    devicePixelRatio: window.devicePixelRatio || 1,
    // Mantén cualquier otra opción que viniera en config.options
    ...config.options,
  };

  const canvas = document.getElementById(canvasId);
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
