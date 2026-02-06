import { useEffect, useState } from "react";
import axios from "axios";
import { router } from "@inertiajs/react";

export function useAuthGuard() {
  const [state, setState] = useState({ loading: true, user: null });

  useEffect(() => {
    let cancelled = false;

    async function load() {
      const token = localStorage.getItem("token");
      if (!token) {
        router.visit("/login");
        return;
      }

      try {
        const res = await axios.get("/api/user"); // your endpoint
        if (!cancelled) setState({ loading: false, user: res.data });
      } catch (e) {
        localStorage.removeItem("token");
        if (!cancelled) router.visit("/login");
      }
    }

    load();
    return () => { cancelled = true; };
  }, []);

  return state; // { loading, user }
}