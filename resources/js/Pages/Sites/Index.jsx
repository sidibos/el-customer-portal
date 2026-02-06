import { useEffect, useState } from "react";
import axios from "axios";
import { Button, Card, ListGroup } from "react-bootstrap";
import { Link } from "@inertiajs/react";
import AppLayout from "../../Components/AppLayout";
import Loading from "../../Components/Loading";

export default function SitesIndex() {
  const [sites, setSites] = useState(null);
  const [selectedSiteId, setSelectedSiteId] = useState(null);

  useEffect(() => {
    let cancelled = false;
    axios.get("/api/sites").then((res) => {
      if (cancelled) return;
      setSites(res.data);
      setSelectedSiteId(res.data?.[0]?.id ?? null);
    });
    return () => { cancelled = true; };
  }, []);

  if (!sites) return <Loading />;

  return (
    <AppLayout selectedSiteId={selectedSiteId} onSelectSite={setSelectedSiteId}>
      <h4 className="mb-3">Sites</h4>

      <Card>
        <ListGroup variant="flush">
          {sites.map((s) => (
            <ListGroup.Item key={s.id} className="d-flex justify-content-between align-items-center">
              <div>
                <div className="fw-semibold">{s.name}</div>
                <div className="text-muted small">{s.address || "—"}</div>
              </div>
              <Button as={Link} href={`/sites/${s.id}/meters`} size="sm" variant="outline-primary">
                View meters
              </Button>
            </ListGroup.Item>
          ))}
        </ListGroup>
      </Card>
    </AppLayout>
  );
}