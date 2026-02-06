import { useEffect, useState } from "react";
import axios from "axios";
import { Badge, Card, ListGroup } from "react-bootstrap";
import { Link, usePage } from "@inertiajs/react";
import AppLayout from "../../Components/AppLayout";
import Loading from "../../Components/Loading";
import ConsumptionBars from "../../Components/ConsumptionBars";

export default function SiteMeters() {
  const { siteId } = usePage().props; // passed from route closure
  const [payload, setPayload] = useState(null);
  const [consumption, setConsumption] = useState(null);
  const [selectedSiteId, setSelectedSiteId] = useState(siteId);

  useEffect(() => {
    let cancelled = false;

    async function load() {
      const [mRes, cRes] = await Promise.all([
        axios.get(`/api/sites/${siteId}/meters`),
        axios.get(`/api/sites/${siteId}/consumption?months=6`),
      ]);
      if (cancelled) return;
      setPayload(mRes.data);
      setConsumption(cRes.data);
    }

    load();
    return () => { cancelled = true; };
  }, [siteId]);

  if (!payload) return <Loading />;

  return (
    <AppLayout selectedSiteId={selectedSiteId} onSelectSite={setSelectedSiteId}>
      <h4 className="mb-3">Site meters</h4>

      <Card>
        <ListGroup variant="flush">
          {payload.meters.map((m) => (
            <ListGroup.Item key={m.id} className="p-0">
              <Link
                href={`/meters/${m.id}`}
                className="d-block p-3 text-decoration-none text-reset"
              >
                <div className="d-flex justify-content-between align-items-start">
                  <div>
                    <div className="fw-semibold">{m.meterId}</div>
                    <div className="text-muted small">
                      Latest: {m.latestReading ?? "—"} • Updated: {m.lastUpdated ? new Date(m.lastUpdated).toLocaleString() : "—"}
                    </div>
                  </div>
                  <Badge bg={m.type === "electric" ? "primary" : "success"}>{m.type}</Badge>
                </div>
              </Link>
            </ListGroup.Item>
          ))}
        </ListGroup>
      </Card>

      <ConsumptionBars title="Site consumption (last 6 months)" data={consumption?.data || []} />
    </AppLayout>
  );
}