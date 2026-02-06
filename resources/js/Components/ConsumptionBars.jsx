import { Card } from "react-bootstrap";

export default function ConsumptionBars({ title, data = [] }) {
  const rows = [...data].slice().reverse();
  const max = Math.max(1, ...rows.map((r) => Number(r.usage)));

  return (
    <Card className="mt-3">
      <Card.Body>
        <Card.Title className="mb-3">{title}</Card.Title>

        {rows.length === 0 ? (
          <div className="text-muted">No consumption data</div>
        ) : (
          <div className="d-flex flex-column gap-2">
            {rows.map((r) => {
              const label = String(r.month).slice(0, 7);
              const pct = Math.round((Number(r.usage) / max) * 100);

              return (
                <div key={r.month} className="d-flex align-items-center gap-2">
                  <div style={{ width: 72 }} className="text-muted small">{label}</div>
                  <div className="flex-grow-1">
                    <div className="bg-light rounded" style={{ height: 12 }}>
                      <div className="bg-primary rounded" style={{ width: `${pct}%`, height: 12 }} />
                    </div>
                  </div>
                  <div style={{ width: 72 }} className="text-end small">
                    {Number(r.usage).toFixed(0)}
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </Card.Body>
    </Card>
  );
}