import { Container, Spinner } from "react-bootstrap";

export default function Loading() {
  return (
    <Container className="py-5 text-center">
      <Spinner animation="border" role="status" />
    </Container>
  );
}