require("dotenv").config();

const apiBaseUrl = (process.env.SMOKE_API_BASE_URL || "http://localhost:3000")
  .replace(/\/$/, "");

const smokeData = {
  enabledUser: process.env.SMOKE_ENABLED_USER || "JMARQUEZ",
  disabledUser: process.env.SMOKE_DISABLED_USER || "USRNOHAB",
  openRequest: process.env.SMOKE_OPEN_REQUEST || "1001001",
  closedRequest: process.env.SMOKE_CLOSED_REQUEST || "1001003"
};

async function request(path, body) {
  const response = await fetch(`${apiBaseUrl}${path}`, {
    method: body ? "POST" : "GET",
    headers: body ? { "Content-Type": "application/json" } : undefined,
    body: body ? JSON.stringify(body) : undefined
  });

  const data = await response.json();

  if (!response.ok) {
    throw new Error(`${response.status} ${JSON.stringify(data)}`);
  }

  return data;
}

function assert(condition, message) {
  if (!condition) {
    throw new Error(message);
  }
}

const tests = [
  {
    name: "API disponible",
    run: async () => {
      const result = await request("/health");
      assert(result.ok === true, "El health check no retorno ok=true");
    }
  },
  {
    name: "Usuario habilitado",
    run: async () => {
      const result = await request("/api/auth/validate", {
        usuarioRed: smokeData.enabledUser
      });
      assert(Number(result.codigo) === 0, "El usuario esperado no esta habilitado");
    }
  },
  {
    name: "Usuario no habilitado",
    run: async () => {
      const result = await request("/api/auth/validate", {
        usuarioRed: smokeData.disabledUser
      });
      assert(Number(result.codigo) !== 0, "El usuario de rechazo fue habilitado");
    }
  },
  {
    name: "Solicitud abierta",
    run: async () => {
      const result = await request("/api/solicitud/consultar", {
        numeroSolicitud: smokeData.openRequest
      });
      assert(Number(result.codigo) === 0, "La solicitud abierta fue rechazada");
    }
  },
  {
    name: "Solicitud cerrada",
    run: async () => {
      const result = await request("/api/solicitud/consultar", {
        numeroSolicitud: smokeData.closedRequest
      });
      assert(Number(result.codigo) !== 0, "La solicitud cerrada fue aceptada");
    }
  },
  {
    name: "Consulta de adjuntos",
    run: async () => {
      const result = await request("/api/adjuntos/consultar", {
        numeroSolicitud: smokeData.openRequest,
        usuarioRed: smokeData.enabledUser
      });
      assert(Array.isArray(result) && result.length > 0, "No se recibieron adjuntos");
      assert(
        result.every((item) => Number(item.codigo) === 0),
        "La consulta de adjuntos retorno un error"
      );
    }
  }
];

async function run() {
  let failures = 0;

  for (const test of tests) {
    try {
      await test.run();
      console.log(`OK   ${test.name}`);
    } catch (error) {
      failures += 1;
      console.error(`FAIL ${test.name}: ${error.message}`);
    }
  }

  if (failures) {
    console.error(`\n${failures} prueba(s) fallaron.`);
    process.exitCode = 1;
    return;
  }

  console.log(`\n${tests.length} pruebas completadas correctamente.`);
}

run().catch((error) => {
  console.error(`No fue posible ejecutar las pruebas: ${error.message}`);
  process.exitCode = 1;
});

