<template>
  <div class="container-fluid p-4">
    <!-- Header with Search -->
    <div class="row align-items-center mb-4">
      <div class="col-md-6">
        <h2 class="mb-0">Data Inventaris</h2>
      </div>
      <div class="col-md-6">
        <div class="d-flex gap-2 justify-content-end">
          <div class="flex-grow-1" style="max-width: 300px; position: relative">
            <i
              class="bi bi-search"
              style="
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #999;
              "
            ></i>
            <input
              type="text"
              class="form-control form-control-sm search-input"
              placeholder="Search Inventaris"
              v-model="searchQuery"
              style="padding-left: 36px"
            />
          </div>
          <button class="btn btn-dark btn-sm" @click="openAddModal">
            <i class="bi bi-plus"></i> Tambah Data
          </button>
        </div>
      </div>
    </div>
    <!-- Table -->
    <div class="table-responsive bg-white rounded">
      <table class="table table-hover mb-0">
        <thead>
          <tr class="table-header">
            <th>No</th>
            <th>Inventaris ID</th>
            <th>Barang</th>
            <th>Type</th>
            <th>Seria Number</th>
            <th>Spesifikasi</th>
            <th>Status</th>
            <th>Assign</th>
            <th>Department</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in filteredInventaris" :key="item.id">
            <td>{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
            <td>{{ item.inventarisId }}</td>
            <td>{{ item.barang }}</td>
            <td>{{ item.type }}</td>
            <td>{{ item.seriaNumber }}</td>
            <td>{{ item.spesifikasi }}</td>
            <td>
              <span :class="getStatusBadgeClass(item.status)">{{
                item.status
              }}</span>
            </td>
            <td>
              <span class="badge bg-light text-dark">{{
                item.assignName
              }}</span>
            </td>
            <td>{{ item.department }}</td>
            <td class="text-center">
              <div class="dropdown">
                <button
                  class="btn btn-sm action-btn"
                  type="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu action-dropdown">
                  <li>
                    <a
                      href="#"
                      @click.prevent="editItem(item)"
                      class="action-item"
                      >Edit</a
                    >
                  </li>
                  <li>
                    <a
                      href="#"
                      @click.prevent="deleteItem(item.id)"
                      class="action-item"
                      >Delete</a
                    >
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
      <div class="d-flex align-items-center gap-2">
        <span class="text-muted">Showing</span>
        <select
          v-model.number="itemsPerPage"
          class="form-select form-select-sm"
          style="width: auto; display: inline-block"
        >
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="15">15</option>
          <option value="20">20</option>
        </select>
        <span class="text-muted">
          of <strong>{{ filteredInventaris.length }}</strong>
        </span>
      </div>
      <nav>
        <ul class="pagination mb-0">
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <a
              class="page-link pagination-link"
              href="#"
              @click.prevent="previousPage"
              >Previous</a
            >
          </li>
          <li
            v-for="page in totalPages"
            :key="page"
            class="page-item"
            :class="{ active: page === currentPage }"
          >
            <a
              class="page-link pagination-link"
              href="#"
              @click.prevent="goToPage(page)"
              >{{ page }}</a
            >
          </li>
          <li
            class="page-item"
            :class="{ disabled: currentPage === totalPages }"
          >
            <a
              class="page-link pagination-link"
              href="#"
              @click.prevent="nextPage"
              >Next</a
            >
          </li>
        </ul>
      </nav>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">
              {{ isEditing ? "Edit Data" : "Tambah Data" }}
            </h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body pt-3">
            <form>
              <div class="form-group mb-3">
                <label class="form-label fw-bold text-muted small"
                  >Inventaris ID</label
                >
                <input
                  type="text"
                  class="form-control form-control-custom"
                  :value="
                    isEditing ? newItem.inventarisId : '(akan auto-generate)'
                  "
                  disabled
                />
              </div>
              <div class="form-group mb-3">
                <label class="form-label fw-bold text-muted small"
                  >Barang</label
                >
                <input
                  type="text"
                  class="form-control form-control-custom"
                  placeholder="Ex: Laptop Lenovo"
                  v-model="newItem.barang"
                />
              </div>
              <div class="form-group mb-3">
                <label class="form-label fw-bold text-muted small">Type</label>
                <input
                  type="text"
                  class="form-control form-control-custom"
                  placeholder="Ex: Laptop"
                  v-model="newItem.type"
                />
              </div>
              <div class="form-group mb-3">
                <label class="form-label fw-bold text-muted small"
                  >Serial Number</label
                >
                <input
                  type="text"
                  class="form-control form-control-custom"
                  placeholder="Ex: SN-LEN-001"
                  v-model="newItem.seriaNumber"
                />
              </div>
              <div class="form-group mb-3">
                <label class="form-label fw-bold text-muted small"
                  >Spesifikasi</label
                >
                <input
                  type="text"
                  class="form-control form-control-custom"
                  placeholder="Ex: RAM 16GB, SSD 512GB"
                  v-model="newItem.spesifikasi"
                />
              </div>
              <div class="form-group mb-3">
                <label class="form-label fw-bold text-muted small"
                  >Status</label
                >
                <select
                  class="form-select form-control-custom"
                  v-model="newItem.status"
                >
                  <option value="Baik">Baik</option>
                  <option value="Rusak">Rusak</option>
                  <option value="Tidak Dipakai">Tidak Dipakai</option>
                  <option value="Dilelang">Dilelang</option>
                </select>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold text-muted small"
                    >Assign To</label
                  >
                  <select
                    class="form-select form-control-custom"
                    v-model="newItem.assign"
                    @change="onMemberSelected"
                  >
                    <option value="">-- Pilih Member --</option>
                    <option
                      v-for="member in members"
                      :key="member.id"
                      :value="member.id"
                    >
                      {{ member.nama }}
                    </option>
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label fw-bold text-muted small"
                    >Department</label
                  >
                  <select
                    class="form-select form-control-custom"
                    v-model="newItem.department"
                  >
                    <option value="">-- Pilih Department --</option>
                    <option value="Technology">Technology</option>
                    <option value="Operations">Operations</option>
                    <option value="Finance">Finance</option>
                    <option value="Marketing">Marketing</option>
                    <option value="HR">HR</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button
              type="button"
              class="btn btn-outline-secondary"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
            <button
              type="button"
              class="btn btn-dark fw-bold"
              @click="saveItem"
            >
              {{ isEditing ? "Save Changes" : "Tambah Data" }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-delete">
          <div class="modal-body text-center pt-5">
            <div class="delete-icon mb-4">
              <i class="bi bi-trash"></i>
            </div>
            <h5 class="fw-bold mb-3">Delete Data</h5>
            <p class="text-muted mb-2">
              Are you sure you want to delete
              <strong>{{ deleteItemName }}</strong>
            </p>
            <p class="text-muted small mb-4">
              with ID <strong>{{ deleteItemId }}</strong
              >? This action cannot be undone
            </p>
            <button
              type="button"
              class="btn btn-danger btn-delete w-100 fw-bold mb-3"
              @click="confirmDelete"
            >
              Delete Data
            </button>
            <button
              type="button"
              class="btn btn-link text-dark"
              data-bs-dismiss="modal"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Modal } from "bootstrap";
import api from "../services/api";

export default {
  name: "DataInventaris",
  setup() {
    const inventaris = ref([]);
    const members = ref([]);
    const loading = ref(true);
    const searchQuery = ref("");
    const currentPage = ref(1);
    const itemsPerPage = ref(5);
    const isEditing = ref(false);
    const editingId = ref(null);
    const newItem = ref({
      inventarisId: "",
      barang: "",
      type: "",
      seriaNumber: "",
      spesifikasi: "",
      status: "Baik",
      assign: null,
      department: "",
    });

    // Function to auto-fill department when member is selected
    const onMemberSelected = () => {
      if (newItem.value.assign) {
        const selectedMember = members.value.find(
          (m) => m.id === newItem.value.assign,
        );
        if (selectedMember && selectedMember.department) {
          newItem.value.department = selectedMember.department;
        }
      }
    };

    // Fetch members from API
    const fetchMembers = async () => {
      try {
        const response = await api.get("/members");
        members.value = response.data.data.map((item) => ({
          id: item.id,
          nama: item.name,
          department: item.departement,
        }));
      } catch (error) {
        console.error("Error fetching members:", error);
      }
    };

    const filteredInventaris = computed(() => {
      return inventaris.value.filter((item) => {
        const searchLower = searchQuery.value.toLowerCase();
        return (
          (item.barang || "").toLowerCase().includes(searchLower) ||
          (item.inventarisId || "").toLowerCase().includes(searchLower) ||
          (item.assignName || "-").toLowerCase().includes(searchLower)
        );
      });
    });

    const totalPages = computed(() => {
      return Math.ceil(filteredInventaris.value.length / itemsPerPage.value);
    });

    // Fetch inventaris from API
    const fetchInventaris = async () => {
      try {
        const response = await api.get("/inventories");
        // Map API response to match our data structure
        inventaris.value = response.data.data.map((item) => {
          // Find member name if assigned
          const assignedMember = members.value.find(
            (m) => m.id === item.assigned_to,
          );
          return {
            id: item.id,
            inventarisId: item.inventaris_id || item.id,
            barang: item.item_name,
            type: item.type,
            seriaNumber: item.serial_number,
            spesifikasi: item.specification,
            status: item.status,
            assign: item.assigned_to,
            assignName: assignedMember ? assignedMember.nama : "-",
            department: item.department,
          };
        });
      } catch (error) {
        console.error("Error fetching inventaris:", error);
      } finally {
        loading.value = false;
      }
    };

    const getStatusBadgeClass = (status) => {
      switch (status) {
        case "Baik":
          return "status-badge status-baik";
        case "Rusak":
          return "status-badge status-rusak";
        case "Tidak Dipakai":
          return "status-badge status-tidak-dipakai";
        case "Dilelang":
          return "status-badge status-dilelang";
        default:
          return "status-badge status-tidak-dipakai";
      }
    };

    let addModalInstance = null;
    let deleteModalInstance = null;

    onMounted(async () => {
      // Load members first, then inventaris
      await fetchMembers();
      await fetchInventaris();

      // Listen for member deletion event to refresh inventory
      const handleMemberDeleted = async () => {
        console.log("Member deleted, refreshing inventory...");
        await fetchMembers(); // Refresh members
        await fetchInventaris(); // Refresh inventaris
      };
      window.addEventListener("memberDeleted", handleMemberDeleted);

      // Initialize modals once on mount
      setTimeout(() => {
        const addModalEl = document.getElementById("addModal");
        const deleteModalEl = document.getElementById("deleteModal");
        if (addModalEl) {
          addModalInstance = new Modal(addModalEl, {
            backdrop: "static",
            keyboard: false,
          });
        }
        if (deleteModalEl) {
          deleteModalInstance = new Modal(deleteModalEl, {
            backdrop: "static",
            keyboard: false,
          });
        }
      }, 100);
    });

    // Cleanup event listener on unmount
    onUnmounted(() => {
      window.removeEventListener("memberDeleted", async () => {
        await fetchMembers();
        await fetchInventaris();
      });
    });

    const openAddModal = () => {
      isEditing.value = false;
      editingId.value = null;
      newItem.value = {
        inventarisId: "",
        barang: "",
        type: "",
        seriaNumber: "",
        spesifikasi: "",
        status: "Baik",
        assign: null,
        department: "",
      };
      if (addModalInstance) {
        addModalInstance.show();
      }
    };

    const editItem = (item) => {
      isEditing.value = true;
      editingId.value = item.id;
      newItem.value = {
        inventarisId: item.inventarisId,
        barang: item.barang,
        type: item.type,
        seriaNumber: item.seriaNumber,
        spesifikasi: item.spesifikasi,
        status: item.status,
        assign: item.assign,
        department: item.department,
      };
      if (addModalInstance) {
        addModalInstance.show();
      }
    };

    const saveItem = async () => {
      try {
        const payload = {
          item_name: newItem.value.barang,
          type: newItem.value.type,
          serial_number: newItem.value.seriaNumber,
          specification: newItem.value.spesifikasi,
          status: newItem.value.status,
          assigned_to: newItem.value.assign || null,
          department: newItem.value.department,
        };

        if (isEditing.value) {
          // Update existing item
          await api.put(`/inventories/${editingId.value}`, payload);
          const memberData = members.value.find(
            (m) => m.id === newItem.value.assign,
          );
          // Update local state
          const index = inventaris.value.findIndex(
            (item) => item.id === editingId.value,
          );
          if (index !== -1) {
            inventaris.value[index] = {
              id: inventaris.value[index].id,
              inventarisId: inventaris.value[index].inventarisId,
              barang: newItem.value.barang,
              type: newItem.value.type,
              seriaNumber: newItem.value.seriaNumber,
              spesifikasi: newItem.value.spesifikasi,
              status: newItem.value.status,
              assign: newItem.value.assign,
              assignName: memberData ? memberData.nama : "-",
              department: newItem.value.department,
            };
          }
        } else {
          // Create new item - backend will generate inventaris_id
          const response = await api.post("/inventories", payload);
          const memberData = members.value.find(
            (m) => m.id === newItem.value.assign,
          );
          // Add to local state with properly mapped fields
          inventaris.value.push({
            id: response.data.data.id,
            inventarisId: response.data.data.inventaris_id,
            barang: newItem.value.barang,
            type: newItem.value.type,
            seriaNumber: newItem.value.seriaNumber,
            spesifikasi: newItem.value.spesifikasi,
            status: newItem.value.status,
            assign: newItem.value.assign,
            assignName: memberData ? memberData.nama : "-",
            department: newItem.value.department,
          });
        }

        // Reset form
        newItem.value = {
          inventarisId: "",
          barang: "",
          type: "",
          seriaNumber: "",
          spesifikasi: "",
          status: "Baik",
          assign: null,
          department: "",
        };
        isEditing.value = false;
        editingId.value = null;

        // Close modal on success
        if (addModalInstance) {
          addModalInstance.hide();
        }
      } catch (error) {
        console.error("Error saving item:", error);
        alert(
          "Error saving item: " +
            (error.response?.data?.message || error.message),
        );
      }
    };

    const deleteItemId = ref("");
    const deleteItemName = ref("");

    const deleteItem = (id) => {
      const item = inventaris.value.find((i) => i.id === id);
      if (item) {
        deleteItemId.value = item.inventarisId;
        deleteItemName.value = item.barang;
        editingId.value = id;
        if (deleteModalInstance) {
          deleteModalInstance.show();
        }
      }
    };

    const confirmDelete = async () => {
      try {
        await api.delete(`/inventories/${editingId.value}`);
        // Remove from local state
        inventaris.value = inventaris.value.filter(
          (item) => item.id !== editingId.value,
        );
        if (deleteModalInstance) {
          deleteModalInstance.hide();
        }
        editingId.value = null;
        deleteItemId.value = "";
        deleteItemName.value = "";
      } catch (error) {
        console.error("Error deleting item:", error);
        alert("Error deleting item. Please try again.");
      }
    };

    const previousPage = () => {
      if (currentPage.value > 1) currentPage.value--;
    };
    const nextPage = () => {
      if (currentPage.value < totalPages.value) currentPage.value++;
    };
    const goToPage = (page) => {
      currentPage.value = page;
    };

    return {
      inventaris,
      members,
      loading,
      searchQuery,
      filteredInventaris,
      currentPage,
      itemsPerPage,
      totalPages,
      newItem,
      isEditing,
      deleteItemId,
      deleteItemName,
      getStatusBadgeClass,
      onMemberSelected,
      openAddModal,
      editItem,
      saveItem,
      deleteItem,
      confirmDelete,
      previousPage,
      nextPage,
      goToPage,
    };
  },
};
</script>

<style scoped>
* {
  font-family: "Manrope", sans-serif;
  font-weight: 600;
  font-size: 16px;
}

.table tbody td {
  padding: 1.2rem 0.75rem;
  font-weight: 400;
}

.table thead th {
  padding: 1.2rem 0.75rem;
  font-weight: 600;
}

.search-input {
  border-radius: 0.5rem;
  background-color: #e8e8e8;
  border: none;
}

.search-input:focus {
  background-color: #e8e8e8;
  border: 1px solid #999;
  box-shadow: none;
}

.table-header {
  background-color: white;
  border-bottom: 2px solid #dee2e6;
  font-weight: 600;
  color: #333;
}

.table-hover tbody tr:hover {
  background-color: #f8f9fa;
}

.status-badge {
  padding: 0.4rem 0.8rem;
  border-radius: 0.375rem;
  font-weight: 500;
  font-size: 0.9rem;
}

.status-baik {
  color: #22863a;
  background-color: #d4edda;
}

.status-rusak {
  color: #721c24;
  background-color: #f8d7da;
}

.status-tidak-dipakai {
  color: #383d41;
  background-color: #e2e3e5;
}

.status-dilelang {
  color: #7a4419;
  background-color: #fff3cd;
}

.pagination-link {
  color: #000 !important;
  border: none;
  background: none;
  padding: 0.5rem 0.7rem;
  text-decoration: none;
}

.pagination-link:hover {
  color: #000;
}

.page-item {
  margin: 0 4px;
}

.page-item.active .pagination-link {
  background-color: #000 !important;
  color: white !important;
  border-radius: 0.375rem;
  border: 1px solid #000;
}

.page-item.disabled .pagination-link {
  color: #999 !important;
  background-color: transparent;
  cursor: not-allowed;
}

.page-item:first-child.disabled .pagination-link {
  background-color: #e9ecef;
  color: #999 !important;
}

.page-item:last-child.disabled .pagination-link {
  background-color: #e9ecef;
  color: #999 !important;
}

.form-select-sm {
  border-radius: 0.375rem;
  border: 1px solid #ddd;
}

.action-btn {
  border: 1px solid #ccc;
  border-radius: 0.375rem;
  color: #666;
  padding: 0.4rem 0.5rem;
  background-color: #f5f5f5;
  cursor: pointer;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.action-btn:hover {
  background-color: #e8e8e8;
  border-color: #999;
}

.action-dropdown {
  border: 1px solid #ddd;
  border-radius: 0.375rem;
  padding: 4px;
  min-width: 100px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
  background: white;
}

.action-dropdown li {
  list-style: none;
  margin: 0;
  padding: 0;
}

.action-item {
  display: block;
  padding: 6px 12px;
  margin: 2px;
  background-color: #000 !important;
  color: white !important;
  border-radius: 0.25rem;
  text-decoration: none;
  font-size: 0.9rem;
  text-align: center;
  transition: all 0.2s;
}

.action-item:hover {
  background-color: #333 !important;
  color: white !important;
}

.modal-content-custom {
  border: none;
  border-radius: 1rem;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.form-control-custom {
  border: 1px solid #e0e0e0;
  border-radius: 0.5rem;
  padding: 0.75rem;
  background-color: #f9f9f9;
}

.form-control-custom:focus {
  border-color: #000;
  background-color: white;
  box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
}

.form-label {
  color: #666;
  font-size: 0.85rem;
  margin-bottom: 0.5rem;
}

.modal-content-delete {
  border: none;
  border-radius: 2rem;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.delete-icon {
  width: 80px;
  height: 80px;
  background-color: #f44336;
  border-radius: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
}

.delete-icon i {
  font-size: 2.5rem;
  color: white;
}

.btn-delete {
  background-color: #f44336;
  border: none;
  border-radius: 0.5rem;
  padding: 0.75rem 1rem;
  font-size: 1rem;
}

.btn-delete:hover {
  background-color: #d32f2f;
}
</style>
