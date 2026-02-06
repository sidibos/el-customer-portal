import { useEffect, useState } from "react";
import axios from "axios";
import { Card } from "react-bootstrap";
import { usePage } from "@inertiajs/react";
import AppLayout from "../../Components/AppLayout";
import Loading from "../../Components/Loading";
import ConsumptionBars from "../../Components/ConsumptionBars";

export default function MeterShow() {
  const { meterId } = usePage().props;
  const [meter, setMeter] = useState(null);
  const [consumption, setConsumption] = useState(null);
  const [selectedSiteId, setSelectedSiteId] = useState(null);

  useEffect(() => {
    let cancelled = false;

    async function load() {
      const [mRes, cRes] = await Promise.all([
        axios.get(`/api/meters/${meterId}`),
        axios.get(`/api/meters/${meterId}/consumption?months=6`),
      ]);
      if (cancelled) return;
      setMeter(mRes.data);
      setConsumption(cRes.data);
      setSelectedSiteId(mRes.data?.site?.id ?? null);
    }

    load();
    return () => { cancelled = true; };
  }, [meterId]);

  if (!meter) return <Loading />;

  return (
    <AppLayout selectedSiteId={selectedSiteId} onSelectSite={setSelectedSiteId}>
      <h4 className="mb-3">Meter</h4>

      <Card>
        <Card.Body>
          <div className="fw-semibold">{meter.meterId}</div>
          <div className="text-muted">Type: {meter.type}</div>
          <div className="text-muted">Site: {meter.site?.name}</div>
          <div className="mt-2">
            Latest reading: <span className="fw-semibold">{meter.latestReading ?? "—"}</span>
          </div>
          <div className="text-muted small">
            Updated: {meter.lastUpdated ? new Date(meter.lastUpdated).toLocaleString() : "—"}
          </div>
        </Card.Body>
      </Card>

      <ConsumptionBars title="Meter consumption (last 6 months)" data={consumption?.data || []} />
    </AppLayout>
  );
}